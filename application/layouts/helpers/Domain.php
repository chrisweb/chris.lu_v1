<?php

class Application_Layouts_Helpers_Domain extends Zend_View_Helper_Abstract {

	function domain()
	{

		$domain = '';
	
		$frontController = Zend_Controller_Front::getInstance();
		$boostrap = $frontController->getParam('bootstrap');
	
		try {

			
			$applicationConfiguration = $boostrap->getResource('ApplicationConfiguration');
		
			$domain = $applicationConfiguration->website->domain;
			
		} catch(Exception $e) {
		
			$logger = $boostrap->getResource('ApplicationLogging');
			$logger->log($e);
		
		}
		
		return $domain;

	}
	
}