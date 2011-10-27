<?php

abstract class Dol_Model_Entity
{

	/*
	 * I'm defining the constructor like this so that we can pass it an array
	 * of values and we don't have to use each setter. Discuss whether the
	 * team considers this is a good idea or not.
	 */
	public function __construct($values = array()) {
		foreach ($values as $key => $value) {
			if(property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	public function __set($name, $value)
    {
        if (!property_exists($this, $name)) {
            throw new Exception('Invalid ' . get_class($this) . ' property');
        }
        $this->$name = $value;
    }

    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new Exception('Invalid ' . get_class($this) . ' property');
        }
        return $this->$name;
    }

    public function toArray() {
    	return get_object_vars($this);
    }

    public function hasAttribute($attr) {
    	if(property_exists($this, $attr))
    		return true;
    	return false;
    }

}
