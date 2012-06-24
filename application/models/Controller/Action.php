<?php
class Model_Controller_Action extends Zend_Controller_Action {
	protected function _getUser() {
		return Model_User::getInstance();
	}

    protected function isMobile() {
        $device = Zend_Registry::get('device');
        return $device->getType() == 'mobile' && !$device->getFeature('is_tablet');
    }
}