<?php

class Dol_Model_Forms_Review extends Dol_Model_Forms_Abstract {

    public function init() {

        $this->addElement(new Dol_Model_Forms_Element_LimitedTextarea('description', array(
        	'label' => 'Your Review',
            'rows'  => 6,
        )));
        
        $this->getElement('description')
        	->removeDecorator('Dol_Model_Forms_Decorator_Element');
        
        $this->addElement(new Zend_Form_Element_Submit('submit',
            array('label'=>'Save')));

        parent::init();
    }
}
