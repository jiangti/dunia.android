<?php
class Zend_View_Helper_WebUrl extends Zend_View_Helper_Abstract {
	
	public function webUrl($url) {
		return str_replace($_SERVER['DOCUMENT_ROOT'], '', $url);
	}
	
}