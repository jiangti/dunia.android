<?php
/**
 *
 * As part of the share family of components.
 *
 * @author jiangti
 *
 */
class Service_Share_Mail extends Aw_Service_ServiceAbstract {
	
	public function fetch() {
		
		$options = array(
			'host'     => 'imap.googlemail.com',
            'user'     => 'to@dunia.com.au',
            'password' => 'qwef123r',
			'port'	   => 993,
			'ssl'	   => 'SSL'
		);
		$mail = new Zend_Mail_Storage_Imap($options);
		
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		
		
			foreach ($mail as $index => $message) {
				
				foreach (new RecursiveIteratorIterator($message) as $part) {
					
					$row = $this->_saveMessageToDb($message);
					//$this->notifySharer($row);
					$mail->moveMessage(1, 'Parsed');
				}
			}
		
		unset($mail);
		
		
		
	}
	
	public function notifySharer(Model_DbTable_Row_MailShare $row) {
		
	}
	
	public function importDb() {
		foreach ($this->getMessages() as $messageFilePath) {
			$message = unserialize(file_get_contents($messageFilePath));
			$this->_saveMessageToDb($message);
			unlink($messageFilePath);
		}
	}
	
	protected function _saveMessageToDb(Zend_Mail_Message $message) {
		$table = new Model_DbTable_MailShare();
		$row = $table->createRow();
		$files = array();
		foreach (new RecursiveIteratorIterator($message) as $part) {
			$contentType = strtok($part->contentType, ';');
			if (stripos($contentType, 'text') !== false) {
				$row->subject = $message->subject;
				$row->body = $part->getContent();
				$row->from = $message->from;
				$row->save();
			}
		
			if (stripos(strtok($part->contentType, ';'), 'image') !== false) {
				$section = explode(";", $part->contentType);
				$data = (parse_ini_string($section[1]));
				$ext = pathinfo($data['name'], PATHINFO_EXTENSION);
		
				$path = sprintf(APPLICATION_ROOT . '/public/mail/%d', $row->id);
		
				mkdir($path, true);
				chmod($path, 0777);
		
				$filePath = sprintf($path . '/%s', $data['name']);
				file_put_contents($filePath, base64_decode($part->getContent()));
				$files[] = $filePath;
			}
		}
		
		$row->attachment = json_encode($files);
		$row->save();
		return $row;
	}
	
	protected function _storeMail(Zend_Mail_Message $message) {
		return file_put_contents(APPLICATION_ROOT . '/var/mail/' . uniqid(), serialize($message));
	}
	
	public function getMessages() {
		return glob(APPLICATION_ROOT . '/var/mail/*');
	}
	
}