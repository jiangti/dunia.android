<?php

error_reporting(E_ALL);

use Doctrine\Common\ClassLoader;

defined('APPLICATION_ROOT') | define('APPLICATION_ROOT', dirname(__FILE__) . '/../..');
defined('APPLICATION_PATH') | define('APPLICATION_PATH', APPLICATION_ROOT . '/application');

require APPLICATION_ROOT . '/library/Doctrine/Common/ClassLoader.php';

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_ROOT . '/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);


$application->bootstrap();

/**
 *  Initialize Doctrine
 */

$classLoader =  new ClassLoader('Doctrine', APPLICATION_ROOT . '/library');
$classLoader->register();

use Doctrine\DBAL\DriverManager;

$config = new \Doctrine\DBAL\Configuration();

$connectionParams = array(
    'dbname' => 'DOL_dev',
    'user' => 'root',
    'password' => 'p4ssw0rd',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);

$conn = DriverManager::getConnection($connectionParams);


/**
 *
 * Run the import script
 *
 */

if(!isset($argv[1]) && !file_exists($argv[1])) {
    die("The xml file doesn't exists \n");
}

$venueList = new VenueList();
$venueList->create($argv[1]);

$conn->beginTransaction();

try {
    foreach($venueList->venues as $venue) {
        $conn->insert('venue', array('name' => $venue->name));
        $venue->id = $conn->lastInsertId();

        $venue->cleanAddress();
        $venue->cleanPhone();

        if($venue->addressObject) {
            $conn->insert('address', array(
                'address1' => $venue->addressObject->address1,
                'city' => $venue->addressObject->city,
                'country' => $venue->addressObject->country,
                'state' => $venue->addressObject->state,
                'latitude' => $venue->addressObject->latitude,
                'longitude' => $venue->addressObject->longitude
            ));

            $venue->idAddress = $conn->lastInsertId();
            $conn->update('venue', array('idAddress' => $venue->idAddress), array('id' => $venue->id));
        }

        if($venue->phone) {
            $conn->insert('telephone', array(
                'number' => $venue->phone
            ));
            $venue->idTelephone = $conn->lastInsertId();
            $conn->insert('venueHasTelephone', array(
                'idVenue' => $venue->id,
                'idTelephone' => $venue->idTelephone));
        }
    }

    $conn->commit();

} catch(Exception $e) {
    $conn->rollback();
    echo "ahahah";
    throw $e;
}


/**
 *
 * Parse the xml file 
 * and add venue as object in a list
 *
 */

class VenueList {

    public $venues = array();
    public $xmlFile = '';
    public $vStart = 0;
    public $vEnd = 0;

    public function __construct() {
    }

    public function create($xmlFile) {
        $this->xmlFile = $xmlFile;

        $xml_parser = xml_parser_create();
        xml_set_object($xml_parser, $this);
        xml_set_element_handler($xml_parser, 'startVenue', 'endVenue');

        $fp = fopen($xmlFile, 'r');
        while ($data = fread($fp, 4096)) {
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
            }
        }
        fclose($fp);

        xml_parser_free($xml_parser);
    }

    private function startVenue($xmlParser, $name, $attrs) {
        if($name == 'VENUE') {
            $this->vStart = xml_get_current_byte_index($xmlParser)-(strlen($name)+1);
        }
    }

    private function endVenue($xmlParser, $name) {
        if($name == 'VENUE') {
            $this->vEnd = xml_get_current_byte_index($xmlParser);
            $this->addVenue($this->vStart, $this->vEnd, $this->xmlFile);
        }
    }

    private function addVenue($vStart, $vEnd, $xmlFile) {
        $venue = new Venue($vStart,$vEnd, $xmlFile);
        $this->venues[] = $venue;
    }
}


/**
 *
 * parse xml to retrieve venue properties
 *
 */
class Venue {

    public $name;
    public $address;
    public $phone;

    public $addressObject;

    public $id;
    public $idAddress;

    private $nameOn = false;
    private $addressOn = false;
    private $phoneOn = false;
    

    public function __construct($vStart, $vEnd, $xmlFile) {
        $fp = fopen($xmlFile, 'r');

        $bytes = $vEnd - $vStart;

        fseek($fp,$vStart);

        $xml_parser = xml_parser_create();
        xml_set_object($xml_parser, $this);
        xml_set_element_handler($xml_parser, 'setPropertiesStart', 'setPropertiesEnd');
        xml_set_character_data_handler($xml_parser, "setPropertiesData");

        $data = fread($fp, $bytes);
        xml_parse($xml_parser, $data, feof($fp));

        fclose($fp);

        xml_parser_free($xml_parser);
    }

    private function setPropertiesStart($xmlParser, $name, $attrs) {
        if($name == 'NAME') {
            $this->nameOn = true;
        } 

        if($name == 'ADDRESS') {
            $this->addressOn = true;
        }

        if($name == 'PHONE') {
            $this->phoneOn = true;
        }
    }

    private function setPropertiesData($xmlParser, $data) {
        if($this->nameOn) {
            $this->name = $data;
        }

        if($this->addressOn) {
            $this->address = $data;
        }

        if($this->phoneOn) {
            $this->phone = $data;
        }
    }

    private function setPropertiesEnd() {
        $this->nameOn = false;
        $this->addressOn = false;
        $this->phoneOn = false;
    }

    public function cleanAddress() {
        if(!$this->address) {
            return;
        }

        $this->addressObject = new Address();
        
        // remove new line character
        $this->address = preg_replace('/\x0a/','', $this->address);

        // remove pub name from address
        $this->address = str_replace($this->name,'', $this->address);

        // retrieve city
        preg_match('/(.*),(.*)/', $this->address, $match);

        $this->addressObject->address1 = trim($match[1]);
        $this->addressObject->city = trim($match[2]);
        $this->addressObject->state = 'NSW';
        $this->addressObject->country = 'australia';
        
	    $geocoder = new Dol_Model_Service_Google_Geocoder();
	    $result = $geocoder->geocodeAddress($this->addressObject->formatAddress());
	    if($result['status'] == 'OK') {
            if (is_array($result['results']) && count($result['results'])) {
                $this->addressObject->longitude = $result['results'][0]['geometry']['location']['lng'];
                $this->addressObject->latitude = $result['results'][0]['geometry']['location']['lat'];
            }
        }
    }

    public function cleanPhone() {
        if(!$this->phone) {
            return;
        }

        $this->phone = trim($this->phone);
    }
}

class Address {
    public $address1;
    public $address2;
    public $city;
    public $state;
    public $postCode;
    public $country;
    public $latitude;
    public $longitude;

    public function formatAddress() {
        $output = array(
            $this->address1,
            $this->address2,
            $this->city,
            $this->state,
            $this->postCode, 
            $this->country
        );

        return implode(' ', $output);
    }
}

