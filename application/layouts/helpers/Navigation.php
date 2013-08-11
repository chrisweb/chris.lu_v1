<?php

class Application_Layouts_Helpers_Navigation extends Zend_View_Helper_Abstract {
	
    function navigation() {
    	
		$frontController = Zend_Controller_Front::getInstance();
		
		$moduleName = $frontController->getRequest()->getModuleName();
		
        $navigation = '';
		
		$homeClass = ($moduleName == 'homepage') ? ' class="active"' : '';
		$projectsClass = ($moduleName == 'projects') ? ' class="active"' : '';
		$bookmarksClass = ($moduleName == 'bookmark') ? ' class="active"' : '';
		$readinglistClass = ($moduleName == 'readinglist') ? ' class="active"' : '';
		
		$navigation .= '<ul class="nav">';
			$navigation .= '<li id="first"'.$homeClass.'><a href="'.$this->view->domain().'/" data-transition="flip">'.$this->view->translate('HOME').'</a></li>';
			$navigation .= '<li'.$projectsClass.'><a href="/myprojects" data-transition="flip">'.$this->view->translate('MYPROJECTS').'</a></li>';
			$navigation .= '<li'.$bookmarksClass.'><a href="/mybookmarks" data-transition="flip">'.$this->view->translate('MYBOOKMARKS').'</a></li>';
			$navigation .= '<li id="last"'.$readinglistClass.'><a href="/myreadinglist" data-transition="flip">'.$this->view->translate('MYREADINGLIST').'</a></li>';
		$navigation .= '</ul>';
		
		return $navigation;
        
    }
    
}
