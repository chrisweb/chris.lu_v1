<?php

class Application_Layouts_Helpers_RightSidebar extends Zend_View_Helper_Abstract
{

    public function rightSidebar()
	{

		$front = Zend_Controller_Front::getInstance();
		$moduleName = $front->getRequest()->getModuleName();
		$controllerName = $front->getRequest()->getControllerName();
		$actionName = $front->getRequest()->getActionName();
		
		$resource = $moduleName.'_'.$controllerName.'_'.$actionName;
		
		switch ($resource) {
			case 'homepage_index_index':
				echo $this->view->myProfile();
				echo $this->view->myReadinglist();
				echo $this->view->myNetworkpages();
				break;
			case 'readinglist_index_index':
				echo $this->view->myProfile();
				echo $this->view->myNetworkpages();
				break;
			case 'article_index_read':
				echo $this->view->socialButtons();
				echo $this->view->relatedArticles();
				echo $this->view->articleTags();
				break;
			default:
				echo $this->view->myProfile();
				echo $this->view->myReadinglist();
				echo $this->view->myNetworkpages();
				break;
		}

    }
	
}