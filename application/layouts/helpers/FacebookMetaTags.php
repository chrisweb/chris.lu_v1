<?php

class Application_Layouts_Helpers_FacebookMetaTags extends Zend_View_Helper_Abstract
{

    public function facebookMetaTags()
	{
        
        $facebookCode = '';

        if ($this->view->article !== null) {

            $front = Zend_Controller_Front::getInstance();
            $moduleName = $front->getRequest()->getModuleName();
            $controllerName = $front->getRequest()->getControllerName();
            $actionName = $front->getRequest()->getActionName();

            if ($moduleName == 'article' && $controllerName == 'index' && $actionName == 'read') {

                $facebookCode .= '<meta property="fb:app_id" content="424957510901747" />';
                $facebookCode .= '<meta property="og:locale" content="en" />';
                $facebookCode .= '<meta property="og:title" content="'.$this->view->escape($this->view->article['title']).'" />';
                $facebookCode .= '<meta property="og:url" content="https://chris.lu/article/read/'.$this->view->escape($this->view->article['_id']).'" />';
                $facebookCode .= '<meta property="og:site_name" content="chris.lu" />';
                $facebookCode .= '<meta property="og:type" content="article" />';

                if (array_key_exists('image', $this->view->article) && !empty($this->view->article['image'])) {

                    $facebookCode .= '<meta property="og:image" content="https://chris.lu/upload/images/'.$this->view->article['image'].'" />';

                }

            }
            
        }
        
        return $facebookCode;

    }
	
}