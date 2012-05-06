<?php
class Form_Flag extends Zend_Form {
	public function init() {
		
		parent::init();
		$id = new Zend_Form_Element_Hidden('idPub');

		$type = new Zend_Form_Element_Radio('type');
        $type
			->setMultiOptions(Model_Flag::$flagType)
            ->setSeparator('')
		;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setLabel('Send')
        ;

        $elements = array(
            $type,
            $submit,
            $id
        );

        $this->addElements($elements);

        $subForm = new Form_Address();
        $this->addSubForm($subForm, 'address');

        foreach (range(0, 6) as $index) {
            $subForm = new Form_Deal_Detail();
            $this->addSubForm($subForm, 'detail' . $index);
        }

        $type = new Zend_Form_Element_Radio('type');
        $type
            ->setMultiOptions(Model_Flag::$flagType)
        ;

        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'pub/partials/flag-form.phtml',
        ))));
    }
	
}