<?php

class Application_Layouts_Helpers_CanonicalTag extends Zend_View_Helper_Abstract {
	
    function canonicalTag() {
    	
		$canonicalTag = '';
		
		$front = Zend_Controller_Front::getInstance();
		$moduleName = $front->getRequest()->getModuleName();
		$controllerName = $front->getRequest()->getControllerName();
		$actionName = $front->getRequest()->getActionName();
		
		$resource = $moduleName.'_'.$controllerName.'_'.$actionName;
		
		//Zend_Debug::dump($resource);
		
		switch ($resource) {
			case 'homepage_index_index':
				$page = $front->getRequest()->getParam('page', 1);
				//Zend_Debug::dump($page);
				if ($page == 1) {
					$canonicalTag = '<link rel="canonical" href="https://chris.lu" />';
				} else {
					$canonicalTag = '<link rel="canonical" href="https://chris.lu/articles/'.$page.'" />';
				}
				break;
		}
		
    	return $canonicalTag;
        
    }
    
}
