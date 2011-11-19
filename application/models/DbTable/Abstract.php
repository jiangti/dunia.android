<?php
class DbTable_Abstract extends Aw_Table_Abstract {
	protected $_primary = 'id';
	
	protected $_rowClass = 'DbTable_Row_Abstract';
	protected $_rowsetClass = 'DTable_Rowset_Abstract';
}