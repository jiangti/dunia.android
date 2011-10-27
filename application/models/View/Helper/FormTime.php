<?php
class Dol_Model_View_Helper_FormTime extends Zend_View_Helper_FormElement {

    public function FormTime($name, $value = null, $attribs = null) {
    	
        $hour = $minute = '00';
        
        if ($value) {
        	if ($value instanceof DateTime) {
        		$hour   = $value->format('H');
        		$minute = $value->format('i');
        	} else {
            	list($hour, $minute) = split(':', $value);
        	}
        }

        $helper = new Zend_View_Helper_FormSelect();
        $helper->setView($this->view);

        $hourarray = array('00' => '00', '01' => '01', '02' => '02', '03' => '03',
            '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08',
            '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13',
            '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18',
            '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23');
        
        $minutearray = array('00' => '00', '15' => '15', '30' => '30', '45' => '45');

        return $helper->formSelect($name . '[hour]', $hour, null, $hourarray) . ' : ' .
		       $helper->formSelect($name . '[minutes]', $minute, null, $minutearray);
    }
}
