<?php
class Service_Pub_Lucene extends Aw_Service_ServiceAbstract {
	
	protected static $_luceneIndex;
	
	public function search($text) {
	    $index = $this->_getIndex();
	    return $index->find($text);
	    
	}
	
	public function add(Model_DbTable_Row_Pub $pub) {
		$index = $this->_getIndex();
		
		$doc = new Zend_Search_Lucene_Document();
		/**
		 * Name, Address
		 */
		$doc->addField(Zend_Search_Lucene_Field::keyword('key', $pub->id));
		$doc->addField(Zend_Search_Lucene_Field::unstored('name', (string) $pub));
		$doc->addField(Zend_Search_Lucene_Field::unstored('address', (string) $pub->getAddress()));
		
		foreach ($index->find('key:' . $pub->id) as $delete) {
		    $index->delete($delete->id);
		}
		
		$index->addDocument($doc);
	}
	
	public function delete(Model_DbTable_Row_Pub $pub) {
	    $index = $this->_getIndex();
	}
	
	protected function _getIndex() {
		
		$application = Zend_Registry::get('Zend_Application');
		
		if (!self::$_luceneIndex) {
		    $filename = APPLICATION_ROOT . '/var/lucene/my-index';
		    if (file_exists($filename)) {
    			self::$_luceneIndex = Zend_Search_Lucene::open($filename );
		    } else {
		        self::$_luceneIndex = Zend_Search_Lucene::create($filename );
		    }
		}
		return self::$_luceneIndex;
	}
	
}