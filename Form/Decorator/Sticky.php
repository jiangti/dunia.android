<?php
class Aw_Form_Decorator_Sticky extends Zend_Form_Decorator_Abstract {
	public function render($content) {
		
		$classesDefault = array('sticky');
		if ($classes = $this->getOption('class')) {
			foreach ($classes as $class) {
				$classesDefault[] = $class;
			}	
		}
		
		return sprintf('<div class="%s">%s</div>', implode(" ", $classesDefault), $content);
	}
}