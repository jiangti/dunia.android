<?php
class Aw_ProgressBar_Adapter_Console extends Zend_ProgressBar_Adapter_Console
{
	protected $_elements = array(self::ELEMENT_PERCENT,
			self::ELEMENT_BAR,
			self::ELEMENT_ETA,
			self::ELEMENT_TEXT);
	
	
}