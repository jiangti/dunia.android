<?php
class Aw_Form_Element_YesNo extends Zend_Form_Element_Radio {
	public function init() {
	    $options = array(1 => 'Yes', 0 => 'No');
	    $this
	        ->setMultiOptions($options)
	        ->setSeparator(' ');
	    ;
	}
}