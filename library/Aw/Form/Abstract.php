<?php
abstract class Aw_Form_Abstract extends Zend_Form {
    protected static $_defaultValue = array('' => '-- Select --');
	public function init() {
		parent::init();
		
		$this->addPrefixPath('Aw_Form_Decorator', __DIR__ . '/Decorator/', 'decorator');

		$this->setAttrib('class', 'uniform');

        $this->_addSubmit();
	}
	
	protected function _addSubmit() {
	    $submit = new Zend_Form_Element_Button('submit');
	    $submit
    	    ->setLabel('Submit')
    	    ->setOrder(999)
    	    ->setAttribs(array('class' => 'btn', 'type' => 'submit'))
    	;

	    $this->addElements(array($submit));
	}
	
	protected $_record;
	public function setRecord(Zend_Db_Table_Row_Abstract $record) {
		if ($record->isPersist()) {
			$this->_record = $record;
			$this->setDefaults($record->toArray());
		} else {
			throw new Exception('An uninitialized Row has been passed in.');
		}
		
		$this->setDefaults($record->toArray());
	}
	
	public function getRecord() {
		return $this->_record;
	}
	
	
	public function dualSubmit() {
		$submit = new Zend_Form_Element_Submit('submit1');
		$submit
			->setLabel('Submit')
			->setOrder(-1);
		;
		
		$submit->addDecorator('sticky', array('class' => array('grid_6', 'alpha')));
		
		$this->addElements(array($submit));
	}
	
}