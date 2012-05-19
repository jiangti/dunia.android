<?php

class Aw_Auth_Identity_Facebook extends Aw_Auth_Identity_Generic
{
	protected $_api;

	public function __construct($token)
	{
		$this->_api = new Aw_Resource_Facebook($token);
		$this->_name = 'facebook';
		$this->_id = $this->_api->getId();
	}

	public function getApi()
	{
		return $this->_api;
	}
}
