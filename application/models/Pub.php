<?php
class Model_Pub extends Aw_Model_ModelAbstract {
    public $id;
	public $name;
	/**
	 * @var Model_Address
	 */
	public $address;
	public $email;
	public $url;
	public $active;

	public $promo = array();

	public $idAddress;


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
	    $promosTable = new Model_DbTable_Promo();
	    $select = $promosTable->select()
	        ->where('idPub = ?', $this->id);

	    return $promosTable->fetchAll($select);
	}
	
	public function getById($id) {
	    $pub = Model_DbTable_Pub::retrieveById($id);
	    
	    $this->setFromArray($pub);

	    $address = new Model_Address();
	    $address->setFromArray($pub->getAddress());
	    
	    $this->setAddress($address);
	    
	    return $this;
	}

	public function save() {
	    $pubTable = new Model_DbTable_Pub();

	    if ($this->address) {
	        $this->idAddress = $this->address->save();
	    }
	    
	    if ($this->id) {
	        $pubRow = $pubTable->find($this->id)->current();
	        $pubRow->setFromArray($this->getArray());
	    } else {
	        $pubRow = $pubTable->createRow($this->getArray());
	    }
	    
	    $pubRow->save();
	}
}