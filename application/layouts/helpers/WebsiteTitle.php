<?php

class Application_Layouts_Helpers_WebsiteTitle extends Zend_View_Helper_Abstract {

	function websiteTitle()
	{

		$title = '';
		
		$frontController = Zend_Controller_Front::getInstance();
		$boostrap = $frontController->getParam('bootstrap');
	
		try {
			
			$applicationConfiguration = $boostrap->getResource('ApplicationConfiguration');
		
			$title = $applicationConfiguration->website->title;
			
		} catch(Exception $e) {
		
			$logger = $boostrap->getResource('ApplicationLogging');
			$logger->log($e);
		
		}
		
		return $title;

	}
	
}