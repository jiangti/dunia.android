<?php
abstract class Aw_Service_ServiceAbstract {
	public function init() {}
	
	public function __construct(array $config = array()) {
		$this->init();
	}
	
	public function isNotEmpty(array $data) {
		
		$validator = new Zend_Validate_NotEmpty();
		
		foreach ($data as $name => $value) {
			if (!$validator->isValid($value)) {
				throw new InvalidArgumentException(implode(", ", $validator->getMessages()));
			}
		}
	}
	
}