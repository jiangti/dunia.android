<?php
class Model_DbTable_Row_Log extends Model_DbTable_Row_RowAbstract {
    
    public function __toString() {
    	return $this->message;
    }
    
}