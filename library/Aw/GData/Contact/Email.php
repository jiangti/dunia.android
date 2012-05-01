<?php
class Aw_GData_Contact_Email extends Zend_Gdata_Extension {
    const REL_WORK = 'http://schemas.google.com/g/2005#work';
    const REL_HOME = 'http://schemas.google.com/g/2005#home';
    const REL_OTHER = 'http://schemas.google.com/g/2005#other';

    protected static $_attributes = array('address', 'displayName', 'label', 'rel', 'primary');

    public function __construct() {
        return parent::__construct();
    }

    public function isPrimary() {
        return ($this->primary == 'true' ? true : false);
    }

    public function __get($name) {
		if (in_array($name, self::$_attributes)) {
		    if ($attribute = idx($this->_extensionAttributes, $name)) {
		        return $attribute['value'];
		    } else {
		        return null;
		    }
		} else {
			$get = parent::__get($name);
		}
		return $get;
	}
    public function __toString() {
    	return $this->address;
    }

    public function toArray() {
    	$data = array(
	    	'address'     => $this->address,
	    	'displayName' => $this->displayName,
	    	'label'	      => $this->label,
	    	'rel'	      => $this->rel,
	    	'primary'     => $this->isPrimary(),
    	);
    	return $data;
    }
}
