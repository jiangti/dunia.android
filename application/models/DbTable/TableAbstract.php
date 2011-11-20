<?php
class Model_DbTable_TableAbstract extends Aw_Table_TableAbstract {
	protected $_primary = 'id';
	
	protected $_rowClass = 'Model_DbTable_Row_RowAbstract';
	protected $_rowsetClass = 'Model_DTable_Rowset_RowsetAbstract';
}