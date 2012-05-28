<?php
abstract class Aw_Table_TableAbstract extends Zend_Db_Table_Abstract {
	protected $_rowClass = 'Aw_Table_Row';
	protected $_rowsetClass = 'Aw_Table_Rowset';

	protected $_dateAdded;
	
	protected static $_table;
	
	public static function getTable() {
        // This is breaking the deals form. We'll discuss on Thursday
		//if (!self::$_table) {
			self::$_table = new static();
		//}
		return self::$_table;
	}

	/**
	 * @return Aw_Table_Row
	 */
	public static function getRow(array $data = array()) {
		$static = new static();
		return $static->createRow($data);
	}

	/**
	 * @return Zend_Db_Table_Select
	 */
	public static function getSelect($withFromPart = self::SELECT_WITHOUT_FROM_PART) {
		$static = self::getTable();
		return $static->select($withFromPart);
	}

	public function getDateAdded() {
		return $this->_dateAdded;
	}

	public static function retrieveById($id) {
		$static = self::getTable();
		return $static->find($id)->current();
	}

	public function getReferenceMap() {
		return $this->_getReferenceMapNormalized();
	}
	
	public static function getOptions() {
		$static = self::getTable();
		return $static->fetchAll()->getPair();
	}
	
	public static function getName() {
		
	}
}
?>