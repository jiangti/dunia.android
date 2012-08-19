<?php
abstract class Aw_Form_Twitter_FormAbstract extends Aw_Form_Abstract {
    protected function _addSubmit() {
        parent::_addSubmit();
        $this->submit->setAttrib('class', 'btn');
    }
}