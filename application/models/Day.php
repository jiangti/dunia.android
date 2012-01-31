<?php
class Model_Day extends Aw_Enum_Abstract {
	const NAME_MON = 'Monday';
	const NAME_TUE = 'Tuesday';
	const NAME_WED = 'Wednesday';
	const NAME_THU = 'Thursday';
	const NAME_FRI = 'Friday';
	const NAME_SAT = 'Saturday';
	const NAME_SUN = 'Sunday';
	
	const INT_MON = 1;
	const INT_TUE = 2;
	const INT_WED = 3;
	const INT_THU = 4;
	const INT_FRI = 5;
	const INT_SAT = 6;
	const INT_SUN = 7;
	
	const ABBR_MON = 'MON';
	const ABBR_TUE = 'TUE';
	const ABBR_WED = 'WED';
	const ABBR_THU = 'THU';
	const ABBR_FRI = 'FRI';
	const ABBR_SAT = 'SAT';
	const ABBR_SUN = 'SUN';
	
	public static $days = array(
		self::INT_MON => self::ABBR_MON,
		self::INT_TUE => self::ABBR_TUE,
		self::INT_WED => self::ABBR_WED,
		self::INT_THU => self::ABBR_THU,
		self::INT_FRI => self::ABBR_FRI,
		self::INT_SAT => self::ABBR_SAT,
		self::INT_SUN => self::ABBR_SUN,
	);
	
	public static function csvToInt($csv) {
		$int = array();
		foreach (explode(",", $csv) as $abbr) {
			$abbr = trim($abbr);
			$int[] = array_search($abbr, self::$days);
		}
		return $int;
	}
	
	public function getAbbr() {
		$consts = $this->_getConstantList();
		$key = array_search($this->_value, $consts);
		if (stripos($key, 'INT') !== false) {
			$key = str_replace('INT', 'ABBR', $key);
			return $consts[$key];
		}
	}
}