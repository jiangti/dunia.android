<?php

use Doctrine\ORM\EntityManager,
	Doctrine\ORM\Configuration;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctrine() {
		$config = new Configuration;

		$cache = new \Doctrine\Common\Cache\ApcCache;
		$config->setMetadataCacheImpl($cache);

		$driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . '/models');
		$config->setMetadataDriverImpl($driverImpl);

		$config->setQueryCacheImpl($cache);
		$config->setProxyDir(APPLICATION_PATH . '/proxies');
		$config->setProxyNamespace('D2Test\Proxies');

		$options = $this->getOption('doctrine');

		$config->setAutoGenerateProxyClasses($options['auto_generate_proxy_class']);

		$em = EntityManager::create($options['db'], $config);
		Zend_Registry::set('EntityManager', $em);

		return $em;
	}

	protected function _initAutoload() {

        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('Dol_');

        return $loader;
    }
    
	protected function _initActionHelpers() {
        $this->bootstrap('frontController');
        Zend_Controller_Action_HelperBroker::addHelper(Zend_Controller_Action_HelperBroker::getStaticHelper('Search'));
    }
    
	public function _initView() {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$viewRenderer->initView();
 
		$viewRenderer->view->addHelperPath(APPLICATION_PATH . '/models/View/Helper/', 'Dol_Model_View_Helper')
            			   ->addHelperPath(APPLICATION_PATH . '/views/helpers/', 'Zend_View_Helper')
           				   ->addScriptPath(APPLICATION_PATH . '/views/scripts');
    }
}

