<?php
class Aw_Validate_GreaterThanElement extends Aw_Validate_Abstract {
	
	const NOT_GREATER = 'notGreaterThan';
	
	protected $_messageTemplates = array(
			self::NOT_GREATER => "'%value%' is not greater than '%min%'",
	);
	
	protected $_messageVariables = array(
			'min' => '_min'
	);
	
	protected $_min;
	
	public function isValid($value, $context = array()) {
		$this->_setValue($value);
		$this->_min = $this->getElement()->getValue();
		
		$min = $this->_min;
		
		if ($min < $value) {
			return true;
		} else {
			$this->_error(self::NOT_GREATER);
			return false;
		}
	}
	
}