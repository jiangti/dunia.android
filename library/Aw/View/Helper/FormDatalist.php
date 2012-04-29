<?php
class Aw_View_Helper_FormDatalist extends Zend_View_Helper_FormText {
    public function formDatalist($name, $value = null, $attribs = null) {
        return $this->formText($name, $value, $attribs);
    }
}