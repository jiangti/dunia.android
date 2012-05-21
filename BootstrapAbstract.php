<?php
abstract class Aw_BootstrapAbstract extends Zend_Application_Bootstrap_Bootstrap {
	public function _initAw() {
		require_once 'Aw.php';
		$view = $this->bootstrap('view')->getResource('view');
		$view->addHelperPath('Aw/View/Helper', 'Aw_Helper');
		
		Zend_Registry::set('Zend_Application', $this);
	}
	
	public function getOptionByDot($string) {
		$option = $this->getOptions();
		$keys = explode('.', $string);
		
		foreach ($keys as $key) {
			$option = $option[$key];
		}
		
		return $option;
	}
	/**
	 * @return Aw_BootstrapAbstract
	 */
	public static function getInstance() {
		return Zend_Registry::get('Zend_Application');
	}
}