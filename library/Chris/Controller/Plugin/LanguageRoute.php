<?php

class Chris_Controller_Plugin_LanguageRoute extends Zend_Controller_Plugin_Abstract
{

	public function routeStartup(Zend_Controller_Request_Abstract $request) {
	
		$applicationConfiguration = Zend_Registry::get('ApplicationConfiguration');
		
		$languageRoutesStatus = $applicationConfiguration->language->routes->status;
		
		if ($languageRoutesStatus) {
		
			$frontController = Zend_Controller_Front::getInstance();
			$bootstrap = $frontController->getParam('bootstrap');
			$locale = $bootstrap->getResource('ApplicationLocale');
			
			$router = $frontController->getRouter();

			$routeLang = new Zend_Controller_Router_Route(
				':lang',
				array(
					'lang' => $locale->getLanguage()
				),
				array('lang' => '[a-z]{2}')
			);

			$languageRoutes = array();
			
			//Zend_Debug::dump($router->getRoutes());
			
			foreach ($router->getRoutes() as $name => $route) {
				$languageRoutes[$name] = $routeLang->chain($route);
				$router->removeRoute($name);
			}
			
			//Zend_Debug::dump($languageRoutes);
			//exit;

			$router->addRoutes($languageRoutes);
		
		}
	
	}

}