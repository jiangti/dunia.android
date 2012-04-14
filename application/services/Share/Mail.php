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
		
		foreach ($mail as $index => $message) {
			foreach (new RecursiveIteratorIterator($message) as $part) {
				$this->_storeMail($message);
				$mail->moveMessage($index, 'Parsed'); 
			}
		}
		
		unset($mail);
		
	}
	
	public function importDb() {
		$table = new Model_DbTable_MailShare();
		foreach ($this->getMessages() as $messageFilePath) {
			$message = unserialize(file_get_contents($messageFilePath));
			$row = $table->createRow();
			$files = array();
			foreach (new RecursiveIteratorIterator($message) as $part) {
				$contentType = strtok($part->contentType, ';');
				if (stripos($contentType, 'text') !== false) {
					$row->subject = $mesasge->subject;
					$row->body = $part->getContent();
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
			
			unlink($messageFilePath);
		}	
	}
	
	protected function _storeMail(Zend_Mail_Message $message) {
		return file_put_contents(APPLICATION_ROOT . '/var/mail/' . uniqid(), serialize($message));
	}
	
	public function getMessages() {
		return glob(APPLICATION_ROOT . '/var/mail/*');
	}
	
}