<?php
class Service_Mailshare {
	public function delete($id) {
		$row = Model_DbTable_MailShare::retrieveById($id);
		$row->delete();
	}
	
	/**
	 * @return Model_DbTable_Row_Pub
	 */
	public function merge($data) {
		try {
			$db = Zend_Db_Table_Abstract::getDefaultAdapter();
			$db->beginTransaction();
			
			$mailShare = Model_DbTable_MailShare::retrieveById($data['id']);
			
			$pub = Model_DbTable_Pub::retrieveById($data['hidden']);
			$pub->mergeMailShare($mailShare);
			
			$mailShare->dateProcessed = date('Y-m-d H:i:s');
			$mailShare->save();
			
			$db->commit();
		} catch (Exception $e) {
			throw $e;
		}
		return $pub;
	}
}