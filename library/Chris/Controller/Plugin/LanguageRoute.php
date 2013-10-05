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
            
            // commented as i don't use any translated routes yet
            // $translate = $bootstrap->getResource('ApplicationTranslations');
            //Zend_Controller_Router_Route::setDefaultTranslator($translate);

            // pass language to the routes as global parameter
            // avoids having to pass it every time you use the url view helper
            //$router->setGlobalParam('language', strtolower(substr($locale->toString(), 0, 2)));

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