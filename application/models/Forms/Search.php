<?php

class Dol_Model_Forms_Search extends Zend_Form 
{
    public function init()
    {
    	$this->setAttribs(array(
    		'id'     => 'searchForm',
    		'action' => '/'
   		));
    	
        $this->addElement(new Zend_Form_Element_Text('searchQuery'));

        $this->addElement(new Zend_Form_Element_Submit('search', array('label' => 'Search')));

        foreach ($this->getElements() as $element) {
        	$element->removeDecorator('Label');
        	$element->removeDecorator('HtmlTag');
        	$element->removeDecorator('DtDdWrapper');
        }

        parent::init();
    }
}
