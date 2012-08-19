<?php
class Form_MailShare extends Aw_Form_Twitter_FormAbstract {
	public function init() {
		parent::init();
		
		$this->_setupElements();
		
		$this->setDecorators(array(
		    'PrepareElements',
		    array('ViewScript', array('viewScript' => 'form/mailshare.phtml')),
		));
	}
	
	public function _setupElements() {
		$subject = new Zend_Form_Element_Text('subject');
		$subject->setLabel('Subject');
		
		$hidden = new Zend_Form_Element_Hidden('hidden');
		$id = new Zend_Form_Element_Hidden('id');
		
		$elements = array($subject, $hidden, $id);
		
		$this->addElements($elements);
		
		

	}
	
	public function setRecord(Zend_Db_Table_Row_Abstract $record) {
		parent::setRecord($record);
		$this->subject->setValue(null)->setAttrib('placeholder', $record->subject);
	}
}