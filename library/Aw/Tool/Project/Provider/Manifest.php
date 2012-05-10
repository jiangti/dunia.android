<?php
class Choc_Tool_Project_Provider_Manifest extends Zend_Tool_Project_Provider_Manifest
{
	public function getProviders()
	{
		$providers = array_merge(parent::getProviders(), array('Aw_Tool_TableProvider', 'Aw_Tool_CodeProvider', "Akrabat_Tool_DatabaseSchemaProvider"));
		return $providers;
	}
}
