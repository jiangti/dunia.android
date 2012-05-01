<?php
trait Aw_Form_Trait {
	protected $_record;
	public function setRecord(Zend_Db_Table_Row_Abstract $record) {
		if ($record->isPersist()) {
			$this->_record = $record;
		} else {
			throw new Exception('An uninitialized Row has been passed in.');
		}
	}
	
}