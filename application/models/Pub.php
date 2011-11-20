<?php
class Model_Pub extends Aw_Model_ModelAbstract {
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
	
}