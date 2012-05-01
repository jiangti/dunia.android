<?php
class Aw_GData_Contact_PhoneNumber extends Zend_Gdata_Extension {
	public function __get($name) {
		if ($attribute = idx($this->_extensionAttributes, $name)) {
			$get = $attribute['value'];
		} else {
			$get = parent::__get($name);
		}		
		return $get;
	}
    public function __toString() {
    	return $this->text;
    }
    
    public function toArray() {
    	$fields = array(
    		'label', 
    		'rel', 
    		'primary', 
    		'uri', 
    		'text'
    	);
    	
    	foreach ($fields as $field) {
    		$data[$field] = $this->$field;
    	}
    	return $data;
    }
}