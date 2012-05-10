<?php
abstract class Aw_BootstrapAbstract extends Zend_Application_Bootstrap_Bootstrap {
	public function _initAw() {
		require_once 'Aw.php';
		$view = $this->bootstrap('view')->getResource('view');
		$view->addHelperPath('Aw/View/Helper', 'Aw_Helper');
	}
}