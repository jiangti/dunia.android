<?php
class Aw_GData_Contact extends Zend_Gdata {

    public function getFeed($location, $className = 'Aw_GData_Contact_Feed') {
        return parent::getFeed($location, $className);
    }

    public function getEntry($location, $className = 'Aw_GData_Contact_Entry') {
        return parent::getEntry($location, $className);
    }

}