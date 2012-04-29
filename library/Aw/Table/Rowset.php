<?php
class Aw_Table_Rowset extends Zend_Db_Table_Rowset_Abstract {
	private $_loadRows;
	
	public function getLoadRows($table, $rule) {

		if ($table instanceof Zend_Db_Table_Abstract) {
			$table = get_class($table);
		}
		$reference = $this->getTable()->getReference($table, $rule);
		
		$rule = ($rule ?: 'none');
		
		if (isset($this->_loadRows[$table])) {
			if (isset($this->_loadRows[$table][$rule])) {
				return $this->_loadRows[$table][$rule];
			}
		}
		
		$dependantTable = new $reference[Zend_Db_Table::REF_TABLE_CLASS];
		$select = $dependantTable->select();
		
		$db = $this->getTable()->getAdapter();
		
		if (sizeof($reference[Zend_Db_Table::COLUMNS]) == 1) {

			$data = array();
			$col = $reference[Zend_Db_Table::COLUMNS][0];

			$data = $this->getCol($col);
			
			$select->where($reference[Zend_Db_Table::REF_COLUMNS][0] . ' in (?)', $data);
		} else {
			
			foreach ($this as $row) {
				foreach ($reference[Zend_Db_Table::COLUMNS] as $index => $col) {
					$whereCols[$reference[Zend_Db_Table::REF_COLUMNS][$index]] = $row->$col;
				}

				$where = array();

				foreach ($whereCols as $index => $value) {
					$where[] = sprintf('(%s = %s)', $index, $db->quote($value));
				}

				$select->orWhere(implode(" AND ", $where));
			}
		}
		
 		$this->_loadRows[$table][$rule] = $dependantTable->fetchAll($select);
 		return $this->_loadRows[$table][$rule];
	}
	
	/**
	 * Provides linking from row to rowset generated.
	 * (non-PHPdoc)
	 * @see Zend_Db_Table_Rowset_Abstract::_loadAndReturnRow()
	 */
	protected function _loadAndReturnRow($position) {
		$row = parent::_loadAndReturnRow($position);
		return $row->setRowset($this);
	}

	public function getReadOnly() {
		return $this->_readOnly;
	}
	
	public function getCol($colName) {
		$data = $this->_data;
		return ipull($data, $colName);
	}

	public function getPair() {
		if ($this->count()) {
		    $data = array();
		    $row = $this->current();
		    $keys = array_keys($row->toArray());
		    $key = $keys[1];
	
	
		    $primary = $this->getTable()->info('primary');
		    if (count($primary) == 1) {
	            $primary = current($primary);
	            foreach ($this->_data as $row) {
	                $data[$row[$primary]] = $row[$key];
	            }
		    } else {
		        throw new Aw_Exception_NotImplement();
		    }
		    return $data;
		} else {
			return array();
		}
	}

}
?>
