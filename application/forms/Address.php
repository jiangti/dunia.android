<?php
class Form_Address extends Zend_Form_SubForm {
    
    public function init() {
    
        parent::init();
        $element = new Zend_Form_Element_Hidden('id');
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('address1', array(
            'label'    => 'Address Line 1',
            'required' => true
        ));
        $this->addElement($element);
                
        $element = new Zend_Form_Element_Text('address2', array(
            'label' => 'Address Line 2'
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('town', array(
            'label'    => 'Suburb',
            'required' => true
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('postcode', array(
            'label'    => 'Postcode',
            'required' => true
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('state', array(
        	'label'    => 'State',
        	'required' => true
        ));
        $this->addElement($element);
    
    }
}