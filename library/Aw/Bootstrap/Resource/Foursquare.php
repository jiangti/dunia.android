<?php
class Aw_Bootstrap_Resource_Foursquare extends Zend_Application_Resource_ResourceAbstract {
    /**
     * @var Aw_Service_Foursquare
     */
    protected $_foursquare;
    
    public function init() {
    	
        $options = $this->getOptions();
        $foursquare = new Aw_Service_Foursquare(
    	    $options['clientId'],
    	    $options['clientSecret'],
    	    $options['accessToken']
        );

        $this->_foursquare = $foursquare;

        return $foursquare;
    }

    /**
     * @return Aw_Service_Foursquare
     */
    public function getFoursquare() {
        return $this->_foursquare;
    }
}