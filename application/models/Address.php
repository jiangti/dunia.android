<?php
class Model_Address extends Aw_Model_ModelAbstract {
    public $id;
	
    public $address1;
	public $address2;
	public $city;
	public $postcode;
	public $town;
	public $state;
	public $country;
	public $latitude;
	public $longitude;
	public $buildingName;

	public function save() {
	    $addressTable = new Model_DbTable_Address();

	    $geocode = Model_Service_Google_Geocoder::geocodeAddress($this->__toString());
	     
	    if ($geocode['status'] == Model_Service_Google_Geocoder::RESPONSE_STATUS_OK) {
	        $this->latitude  = $geocode['results'][0]['geometry']['location']['lat'];
	        $this->longitude = $geocode['results'][0]['geometry']['location']['lng'];
	    }
	    
	    if ($this->id) {
	        $addressRow = $addressTable->find($this->id)->current();
	        $addressRow->setFromArray($this->getArray());
	    } else {
	        $addressRow = $addressTable->createRow($this->getArray());
	    }
	    
	    return $addressRow->save();
	    
	}
	
	public function __toString() {
	    $address = $this->address1;
	    
	    if ($this->address2) {
	        $address .= ' ' . $this->address2;
	    }
	    
	    $address .= ' ' . $this->town . ' ' . $this->state . ' ' . $this->postcode;
	    
	    return $address;
	}
}