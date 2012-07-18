<?php
class Model_Pub extends Aw_Model_ModelAbstract {
    public $id;
	public $name;
	/**
	 * @var Model_Address
	 */
	public $address;
    public $pubType;
	public $email;
	public $url;
	public $active;

	public $promo = array();
	
	public $idFoursquare;
	public $validated;

	public $idAddress;
    public $idPubType;
	
	public $twitter;
	
	public $telephone;
	public $checkinsCount;
	public $isChecked;
	
	protected $_pub = null;


	public function setAddress(Model_Address $address) {
		$this->address = $address;
	}

	public function addPromo(Model_Promo $promo) {
		$this->promo[] = $promo;
		/**
		 * This is where the logic sits, ServiceLayer, POPO, and DAO <= Validation sits here.
		 */
	}
	
	public function getPromos() {
	    $pubRow = Model_DbTable_Pub::retrieveById($this->id);
	    return $pubRow->findManyToManyRowset('Model_DbTable_Promo', 'Model_DbTable_PubHasPromo');
	}
	
	public function getPromosByDay() {
	    $promos = $this->getPromos();
	    
	    $weekly = array();
	    foreach ($promos as $promo) {
	        foreach (Model_Day::$days as $dayInt => $day) {
	            if (strstr($promo->day, $day) !== false) {
	                $weekly[$dayInt][] = $promo;
	            }
	        }
	    }

	    return $weekly;
	}
	
	public function getChangeLogs() {
	    return $this->_pub->getChangeLogs();
	}
	
	public function getTips() {
	    $pubRow = Model_DbTable_Pub::retrieveById($this->id);
	    return $pubRow->findDependentRowset('Model_DbTable_Tip');
	}
	
	public function getById($id) {
	    $pub = Model_DbTable_Pub::retrieveById($id);
	    $this->setFromArray($pub);
	    
	    $this->_pub = $pub;

	    $address = new Model_Address();
	    $address->setFromArray($pub->getAddress());

        $this->setAddress($address);
        $this->pubType = $pub->getPubType();

        return $this;
	}

	public function save() {
	    $pubTable = new Model_DbTable_Pub();

	    if ($this->address) {
	        $this->idAddress = $this->address->save();
	    }

        if ($this->pubType) {
            $this->idPubType = $this->pubType->id;
        }
	    
	    if ($this->id) {
	        $pubRow = $pubTable->find($this->id)->current();
	        $pubRow->setFromArray($this->getArray());
	    } else {
	        $pubRow = $pubTable->createRow($this->getArray());
	    }
	    
	    $pubRow->save();
	}

    public function getImageLinks() {
        $links = array();

        if (is_dir($dir = APPLICATION_PATH . '/../public/images/pub/' . $this->id)) {
            foreach (glob($dir . '/*') as $file) {
                $links[] = '/images/pub/' . $this->id . '/' . basename($file);
            }
        }
        return $links;
    }
}