<?php
class Form_Pub extends Zend_Form {
    
    public function init() {
    
        parent::init();
        
        $element = new Zend_Form_Element_Hidden('id');
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('name', array(
            'label'    => 'Name',
            'required' => true
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('email', array(
            'label' => 'Email'
        ));
        $element->addValidator(new Zend_Validate_EmailAddress());
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('url', array(
            'label' => 'Website'
        ));
        $this->addElement($element);
        
        $this->addSubForm(new Form_Address(), 'address');
        
        $element = new Zend_Form_Element_Button('submit', array(
        	'label' => 'Add',
        	'type'  => 'submit'
        ));
        $this->addElement($element);

    }
}