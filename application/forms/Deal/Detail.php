<?php
class Form_Deal_Detail extends Aw_Form_SubForm_Abstract {
	public function init() {
		$return = parent::init();
		
		$deal = new Zend_Form_Element_Text('value');
		
		$deal
			->setLabel('Value')
		;
		
		$timeRanges = array('' => '-- Select --');
		
		$timeKeys = range(0, 11.5, 0.5);
		
		foreach ($timeKeys as $index => $value) {
			$val = (int) $value;
			if ($index % 2) {
				$timeRanges[$val . ':30'] = $val . ':30';
			} else {
				$timeRanges[$val . ':00'] = $val . ':00';
			}
		}
		
		
		$start = new Zend_Form_Element_Select('start');
		
		$start
			->setLabel('Start Time')
			->setMultiOptions($timeRanges)
		;
		
		
		$end = new Zend_Form_Element_Select('end');
		
		$end
			->setLabel('End Time')
			->setMultiOptions($timeRanges)
		;
		
		$greater = new Aw_Validate_GreaterThanElement();
		$greater->setElement($start);
		
		$end->addValidator($greater);
		
		
		$liquorType = new Zend_Form_Element_MultiCheckbox('liquorType');
		
		$liquorType
			->setLabel('Liquor Type')
			->setMultiOptions(Model_DbTable_LiquorType::getOptions())
			->setSeparator(' ');
		;
		
		$dayOptions = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		
		$days = new Zend_Form_Element_MultiCheckbox('days');
		
		$days
			->setLabel('Days')
			->setMultiOptions($dayOptions)
			->setSeparator(' ');
		;
		
		
		$this->addElements(array($deal, $start, $end, $liquorType, $days));
		
		return $return;
	}
}