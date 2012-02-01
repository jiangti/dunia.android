<?php
class Model_User {
	
	const DEFAULT_LATITUDE  = -33.8757;
	const DEFAULT_LONGITUDE = 151.206;
	
	
	public static function getInstance() {
		return new self();
	}
	
	
	public function getLat() {
		return self::DEFAULT_LATITUDE;
	}
	
	public function getLong() {
		return self::DEFAULT_LONGITUDE;
	}
	
}