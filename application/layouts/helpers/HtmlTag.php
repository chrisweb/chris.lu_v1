<?php

class Application_Layouts_Helpers_HtmlTag extends Zend_View_Helper_Abstract
{

    public function htmlTag()
	{

		$htmlTag = '';
		
		$front = Zend_Controller_Front::getInstance();
		$moduleName = $front->getRequest()->getModuleName();
		$controllerName = $front->getRequest()->getControllerName();
		$actionName = $front->getRequest()->getActionName();
		
		//Zend_Debug::dump($moduleName);
		//Zend_Debug::dump($controllerName);
		//Zend_Debug::dump($actionName);
		
		if ($moduleName == 'article' && $controllerName == 'index' && $actionName == 'read') {
		
			$htmlTag .= '<html itemscope itemtype="http://schema.org/Article" lang="en">';
		
		} else {
		
			$htmlTag .= '<html itemscope itemtype="http://schema.org/Blog" lang="en">';
		
		}
	
		return $htmlTag;

    }
	
}