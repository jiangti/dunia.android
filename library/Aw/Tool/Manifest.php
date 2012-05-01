<?php
class Aw_Tool_Manifest implements Zend_Tool_Framework_Manifest_ProviderManifestable {
	public function getProviders() {
		set_include_path(get_include_path() . PATH_SEPARATOR . AW_ROOT . '/Contrib/Akrabat/zf1');
		return array(
			new Aw_Tool_TableProvider(),
			new Aw_Tool_CodeProvider(),
			new Akrabat_Tool_DatabaseSchemaProvider()
		);
	}
}