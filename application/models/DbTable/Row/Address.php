<?php
class Model_DbTable_Row_Address extends Model_DbTable_Row_RowAbstract {
    
    public function __toString() {
    	return $this->formatOutput();
    }
    
    public function formatOutput($separator = '<br />') {
    	$address = $this->address1 . $separator;
    	
    	if ($this->address2) {
    		$address .= $this->address2 . $separator;
    	
    	}
    	
    	$address .= $this->town . ' ' . $this->state . ' ' . $this->postcode;
    	return $address;
    }
}