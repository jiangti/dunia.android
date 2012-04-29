<?php
class Aw_Form_Element_Date extends Aw_Form_Element_Text {
    public $helper = 'formDate';
    public function init() {
        parent::init();
        $this
            ->setAttrib('class', 'date')
        ;
    }

    public function getValue() {
        $value = parent::getValue();
        $date = new DateTime($value);
        return $date->format('Y-m-d');
    }
}