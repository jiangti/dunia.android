<?php
class Aw_Tool_TableProvider extends Zend_Tool_Framework_Provider_Abstract implements Zend_Tool_Framework_Provider_Interface {
	protected $_namespace = 'Aw_Table';
	protected $_abstractNamespace = 'Aw_Table';

	public function createAction(
		$tableName,
		$namespace = null,
		$abstractNamespace = null) {

		if (!$namespace) {
			$namespace = $this->_namespace;
		}

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
	            'defaultValue' => 'id',
	        ),
		));

		$table->setExtendedClass($abstractNamespace . '_Abstract');
		echo $table->generate();
	}

	public function rowAction() {

	}

	public function rowsetAction() {

	}
}