<?php
class Model_DbTable_Row_Address extends Model_DbTable_Row_RowAbstract {
    
    public function __toString() {
        
        $address = $this->address1 . '<br />';
        
        if ($this->address2) {
            $address .= $this->address2 . '<br />';
            
        }
        
        $address .= $this->town . ' ' . $this->state . ' ' . $this->postcode;
        return $address;
    }
}