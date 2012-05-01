<?php
abstract class Aw_Service_ServiceAbstract {
	public function init() {}
	
	public function __construct(array $config = array()) {
		$this->init();
	}
	
	public function isNotEmpty(array $data) {
		foreach ($data as $name => $value) {
			
		}
	}
}