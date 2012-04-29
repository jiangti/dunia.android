<?php
abstract class Aw_GData_TextAbstract extends Zend_Gdata_Extension {
    public function __construct($text = null) {
        parent::__construct();
        $this->_text = $text;
    }

    public function __toString() {
        return $this->getText();
    }
}