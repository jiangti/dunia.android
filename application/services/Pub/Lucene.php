<?php
class Service_Pub_Lucene extends Aw_Service_ServiceAbstract {
	
	protected static $_luceneIndex;
	
	public function search($text) {
		
	}
	
	public function add(Model_DbTable_Pub $pub) {
		
	}
	
	public function delete(Model_DbTable_Pub $pub) {
		
	}
	
	protected function _getIndex() {
		
		$application = Zend_Registry::get('Zend_Application');
		
		if (!self::$_luceneIndex) {
			self::$_luceneIndex = Zend_Search_Lucene::open();
		}
		return self::$_luceneIndex;
	}
	
}