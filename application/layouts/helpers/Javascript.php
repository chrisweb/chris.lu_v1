<?php

class Application_Layouts_Helpers_Javascript extends Zend_View_Helper_Abstract
{

    public function javascript($staticSource)
	{

		//$javascriptPath = '';
	
		//$this->view->headScript()->appendFile($javascriptPath);
		
		$javascriptTag = '';
		
		$javascriptTag .= '<script data-main="'.$staticSource.'/js/main-2.0.9" src="'.$staticSource.'/vendor/requirejs/require.js"></script>';
		
		return $javascriptTag;
	
    }
	
}