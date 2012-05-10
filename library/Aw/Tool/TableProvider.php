<?php
class Aw_Tool_TableProvider extends Zend_Tool_Framework_Provider_Abstract implements Zend_Tool_Framework_Provider_Interface {
	protected $_namespace = 'Aw_Table';
	protected $_abstractNamespace = 'Aw_Table';

	public function createAction(
		$tableName,
		$abstractNamespace = 'Aw_Table_TableAbstract') {
		
		$namespace = 'Aw_Table_';
		
		$db = Zend_Db_Table::getDefaultAdapter();

		$info = $db->describeTable($tableName);
		
		$primaryKey = $this->_parsePrimaryKey($info);
		

		if (!$abstractNamespace) {
			$abstractNamespace = $this->_abstractNamespace;
		}

		$table = new Zend_CodeGenerator_Php_Class();
		$table->setName($namespace . '_' . ucwords($tableName));
		$table->setProperties(array(
			array(
	            'name'         => '_name',
	            'visibility'   => 'protected',
	            'defaultValue' => $tableName,
	        ),
			array(
	            'name'         => '_primary',
	            'visibility'   => 'protected',
	            'defaultValue' => current($primaryKey),
	        ),
		));

		$table->setExtendedClass($abstractNamespace);
		$class = $table->generate();
		$class = '<?php' . PHP_EOL . $class;
		file_put_contents(APPLICATION_ROOT . '/application/models/DbTable/' . ucwords($tableName) . '.php', $class);
	}

	public function rowAction() {

	}

	public function rowsetAction() {

	}
	
	private function _parsePrimaryKey($info) {
		$primary = array();
		
		foreach ($info as $i) {
			if ($i['PRIMARY']) {
				$primary[] = $i['COLUMN_NAME'];
			}
		}
		
		return $primary;
	}
}