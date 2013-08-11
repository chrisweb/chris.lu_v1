<?php

class Application_Layouts_Helpers_ViewContent extends Zend_View_Helper_Abstract
{

    public function viewContent()
	{

		$viewContent = '';
		
		$front = Zend_Controller_Front::getInstance();
		$moduleName = $front->getRequest()->getModuleName();
		$controllerName = $front->getRequest()->getControllerName();
		$actionName = $front->getRequest()->getActionName();
		
		//Zend_Debug::dump($moduleName);
		//Zend_Debug::dump($controllerName);
		//Zend_Debug::dump($actionName);
		
		if ($moduleName == 'readinglist' && $controllerName == 'index' && $actionName == 'index') {
		
			$viewContent .= $this->view->layout()->content;
		
		} else {
		
			$viewContent .= '<div id="core">';
			$viewContent .= $this->view->layout()->content;
			$viewContent .= '</div>';
		
		}
	
		return $viewContent;

    }
	
}