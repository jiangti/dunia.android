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
		
		$uniqueIds = array();
		
		foreach ($mail as $index => $message) {
			
			$uniqueIds[] = $mail->getUniqueId($index);
			$row = $this->_saveMessageToDb($message);
			$this->notifySharer($row);
			if ($index % 5 == 0) {
				$mail->noop();
			}
		}
		
		$count = 0;
		
		foreach ($uniqueIds as $index => $uniqueId) {
			$count++;
			$mail->moveMessage($mail->getNumberByUniqueId($uniqueId), 'Parsed');
			if ($index % 5 == 0) {
				$mail->noop();
			}
		}
		
			
		unset($mail);
		
		return $count;
		
	}
	
	public function notifySharer(Model_DbTable_Row_MailShare $row) {
		$mail = new Zend_Mail();
		
		preg_match('/(.*)<(.*)>/', $row->from, $match);
		
		list($ignore ,$name, $email) = $match;
		
		$name = trim($name);
		
		$view = new Zend_View();
		
		$view->setScriptPath(APPLICATION_PATH . '/views/mails');
		
		$view->row = $row;
		
		$html = $view->render('share-notification.phtml');
		
		$mail
			->setFrom('no-reply@dunia.com.au', 'NoReply')
			->setBodyHtml($html)
			->setSubject('Upload to dunia.com.au successful.')
			->addTo('jiangti.wan.leong@gmail.com', 'Wan-Leong Jiangti')
			->addTo('victorgarciagonzalez@gmail.com', "Víctor García")
			->addTo($email, $name)
		;
		
		$mail->send();
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
		
				if (!file_exists($part)) {
					mkdir($path, true);
					chmod($path, 0777);
				}
			
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