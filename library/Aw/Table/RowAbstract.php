<?php
abstract class Aw_Table_RowAbstract extends Zend_Db_Table_Row_Abstract {
	/**
	 * @var Zend_Db_Table_Rowset_Abstract
	 */
	protected $_rowset;
	protected static $_cols = null;
	


	public function setRowset($rowset) {
		$this->_rowset = $rowset;
		return $this;
	}
	
	protected function _getCols() {
		return $this->getTable()->info('cols');
	}
	
	protected function _save() {
		$cols = $this->_getCols();

		$fields = array('dateAdded', 'datetimeAdded');
	
		foreach ($fields as $field) {
			if (in_array($field, $cols) !== false) {
				if (!$this->$field) {
					$this->$field = date('Y-m-d H:i:s');
				}
			}
		}
	}
	
	protected function _postSave() {
	}
	
	public function save()
	{
		$this->_save();
		$return = parent::save();
		$this->_postSave();
		return $return;
	}
}