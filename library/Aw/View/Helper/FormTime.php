<?php
class Aw_View_Helper_FormTime extends Zend_View_Helper_FormElement {
    public function formTime($name, $value = null, $attribs = null) {

        $hour = $minute = '00';

        if ($value) {
            if ($value instanceof DateTime) {
                $hour = $value->format('H');
                $minute = $value->format('i');
            } else {
                list($hour, $minute) = split(':', $value);
            }
        }

        $helper = new Zend_View_Helper_FormSelect();
        $helper->setView($this->view);

        $hourarray = $minutearray = array();

        for ($i = 0; $i < 60; $i++) {
            $number = str_pad($i, 2, '0', STR_PAD_LEFT);
            if ($i % 5 == 0) {
                $minutearray[$number] = $number;
            }

            if ($i < 24) {
                $hourarray[$number] = $number;
            }
        }

        return sprintf("%s : %s", $helper->formSelect($name . '[hour]', $hour, null, $hourarray), $helper->formSelect($name . '[minutes]', $minute, null, $minutearray));
    }
}
