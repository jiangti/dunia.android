<?php
class Aw_Table_Row extends Aw_Table_RowAbstract {
	
	public function loadManyToManyRowset() {

	}

	/**
	 * $users->loadDependentRowset('Dv_Table_Divebook');
	 * Enter description here ...
	 */
	public function loadDependentRowset() {

	}
	
	public function findRows($table, $rule = null) {
		if ($this->_rowset) {
			$loadRows = $this->_rowset->getLoadRows($table, $rule);
			
			$rows = $loadRows->toArray();
			
			if ($table instanceof Zend_Db_Table_Abstract) {
				$tableClass = get_class($table);
				$classTable = $table;
			} else {
				$tableClass = $table;
				$classTable = new $table;
			}
			
			$reference = $this->getTable()->getReference($tableClass);
			
			$cols = $reference[Zend_Db_Table_Abstract::COLUMNS];
			
			$prep = array();
			
			foreach ($cols as $index => $value) {
				$prep[$reference[Zend_Db_Table_Abstract::REF_COLUMNS][$index]] = $this->$value;
			}
			
			foreach ($rows as $index => $row) {
				$result = array_intersect_assoc($row, $prep);
				if (count($result) < sizeof($prep)) {
					unset($rows[$index]);
				}
			}
			
			$rows = array_values($rows);
			
			$classTable->info();
			$data  = array(
				'table'    => $classTable,
				'data'     => $rows,
			    'readOnly' => false,
			    'rowClass' => $classTable->getRowClass(),
			    'stored'   => true
			);
			
			$rowsetClass = get_class($loadRows);
			return new $rowsetClass($data);
			
			
		} else {
			return $this->findDependentRowset($table, $rule);
		}
	}

	public function _insert() {
		if ($this->getTable()->getDateAdded()) {
			$this->{$this->getTable()->getDateAdded()} = date('Y-m-d H:i:s');
		}

		parent::_insert();
	}

	public function getRecord($key) {
	}

	public function load($ruleKey) {
		if (!$table = $this->getTable()) {
			$tableClass = $this->getTableClass();
			$table = new $tableClass;
		}
		$def = $table->getReferenceMap();
		$tableClass = $def[$ruleKey][Zend_Db_Table_Abstract::REF_TABLE_CLASS];
		exit;
	}


	/**
	 * Checks if all primary keys have value.
	 */
	public function isPersist() {
		foreach ($this->getTable()->info('primary') as $key) {
			if (!$this->$key) {
				return false;
			}
		}
		return true;
	}
}
?>
