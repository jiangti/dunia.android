<?php
class Service_Mailshare {
	public function delete($id) {
		$row = Model_DbTable_MailShare::retrieveById($id);
		$row->delete();
	}
}