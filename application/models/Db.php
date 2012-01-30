<?php
class Model_Db {
	public static function getInstance() {
		return Zend_Db_Table::getDefaultAdapter();
	}
}