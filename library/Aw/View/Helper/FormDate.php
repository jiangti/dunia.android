<?php
class Aw_View_Helper_FormDate extends Zend_View_Helper_FormText {
    public function formDate($name, $value = null, $attribs = null) {
        $date = new DateTime($value);
        $value = $date->format('j F, Y');
        return $this->formText($name, $value, $attribs);
    }
}