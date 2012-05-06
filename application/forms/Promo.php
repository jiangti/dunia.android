<?php
class Form_Promo extends Zend_Form {

    public function init() {

        parent::init();
        
        $element = new Zend_Form_Element_Hidden('id');
        $element->setDecorators(array('ViewHelper'));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Hidden('idPub');
        $element->setDecorators(array('ViewHelper'));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('price', array(
            'label' => 'Price'
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Radio('idLiquorType', array(
            'multiOptions' => Model_LiquorType::getAllByName(),
            'separator'    => ''
        ));
        
        //if (Zend_Registry::get('device')->getType() == 'mobile') {
            $element->setDecorators(array('ViewHelper', new Aw_Decorator_JQMobileGroup()));
        //}
        
        $this->addElement($element);
        
        $element = new Aw_Form_Element_Time('timeStart', array(
            'label'    => 'From',
            'required' => true,
            'class'    => 'time'
        ));
        $this->addElement($element);
        
        $element = new Aw_Form_Element_Time('timeEnd', array(
            'label'    => 'To',
            'required' => true,
        	'class'    => 'time'
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_MultiCheckbox('day', array(
            'label'	       => 'Days',
            'multiOptions' => Model_Promo::getDaysList(),
            'separator'    => ''
        ));
        
        //if (Zend_Registry::get('device')->getType() == 'mobile') {
            $element->setDecorators(array('ViewHelper', new Aw_Decorator_JQMobileGroup()));
        //}
        
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Textarea('description', array(
            'label' => 'Description'
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Button('submit', array(
        	'label' => 'Add',
        	'type'  => 'submit'
        ));
        $this->addElement($element);

    }
}