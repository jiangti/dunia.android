<?php
class Model_Controller_Action extends Zend_Controller_Action {
	protected function _getUser() {
		return Model_User::getInstance();
	}

    protected function isMobile() {
        $device = Zend_Registry::get('device');
        if ($device->getType() == 'mobile' && $device->getFeature('is_tablet') == 'false') {
            $this->view->isMobile = true;
            return true;
        } return false;
    }
}