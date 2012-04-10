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
		
		foreach ($mail as $message) {
			foreach (new RecursiveIteratorIterator($message) as $part) {
				
				var_dump($message); exit; 
				var_dump($part); 
				var_dump(strtok($part->contentType, ';'));
			    try {
			        if (strtok($part->contentType, ';') == 'text/plain') {
			           //base64_decode($data)
			        } elseif (strtok($part->contentType, ';') == 'text/plain') {
			        	$file = $part->filename;
			        	$content = $part->getContent();
			        	
			        	
			        }
			    } catch (Zend_Mail_Exception $e) {
			        // ignore
			    }
			}
		}
		
		exit ('..');
	}
	
	
}