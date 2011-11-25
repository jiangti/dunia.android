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

	public $promo = array();

	protected $_idAddress;


	public function setAddress(Model_Address $address) {
		$this->address;
	}

	public function addPromo(Model_Promo $promo) {
		$this->promo[] = $promo;
		/**
		 * This is where the logic sits, ServiceLayer, POPO, and DAO <= Validation sits here.
		 */
	}

	public function save() {
	    $pubTable = new Model_DbTable_Pub();

	    if (!$pubRow = $pubTable->findByName($pub->name)) {
	        $pubRow = $pubTable->createRow($pub->getArray());
	    }
	    $pubRow->save();

	    if ($this->address) {
	        $address = $this->address;
	        $address->save();
	    }
	}
}