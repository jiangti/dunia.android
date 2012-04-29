<?php
abstract class Aw_Enum_Abstract {
	protected $_value;
	protected static $_reflection;
	
	/**
	 * Ensures that enum does not have any duplicate values.
	 * @param unknown_type $value
	 * @throws Exception
	 */
	public function __construct($value) {
		$consts = $this->_getConstantList();
		
		$counts = array_count_values($consts);
		
		foreach ($counts as $count) {
			if ($count > 1) {
				throw new Exception('Value in an enum must not repeat.');
			}
		}
		
		if (in_array($value, $consts)) {
			$this->_value = $value;
		} else {
			throw new Exception('Value provided is not in the enumerable values.');
		}
	}
	
	protected function _getConstantList() {
		$className = get_class($this);
		if (!self::$_reflection[$className]) {
			$reflection = new ReflectionObject($this);
			self::$_reflection[$className] = $reflection;
		}
		$reflection = self::$_reflection[$className];
		return $reflection->getConstants();
	}
}