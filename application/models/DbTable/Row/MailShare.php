<?php
class Model_DbTable_Row_MailShare extends Model_DbTable_Row_RowAbstract {
	public function getImages() {
		$wildcard = sprintf(APPLICATION_ROOT . '/public/mail/%d/*', $this->id);
		return glob($wildcard);
	}
	
	public function _delete() {
		$path = $this->getImageDirectory();
		if (file_exists($path)) {
			Aw_FileSystem::rmdir();
		}
	}
	
	public function getImageDirectory() {
		return APPLICATION_ROOT . '/public/mail/' . $this->id;
	}
	
	
}