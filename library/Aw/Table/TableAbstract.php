<?php
abstract class Aw_Table_TableAbstract extends Zend_Db_Table_Abstract {
	protected $_rowClass = 'Aw_Table_Row';
	protected $_rowsetClass = 'Aw_Table_Rowset';

	protected $_dateAdded;

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
		$static = new static();
		return $static->select($withFromPart);
	}

	public function getDateAdded() {
		return $this->_dateAdded;
	}

	public static function retrieveById($id) {
		$table = new static();
		return $table->find($id)->current();
	}

	public function getReferenceMap() {
		return $this->_getReferenceMapNormalized();
	}
	
	public static function getOptions() {
		$table = new static();
		return $table->fetchAll()->getPair();
	}
}
?>