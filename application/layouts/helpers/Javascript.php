<?php

class Application_Layouts_Helpers_Javascript extends Zend_View_Helper_Abstract
{

    public function javascript($staticSource)
	{

		$javascriptTag = '';
        
        if (APPLICATION_ENV === 'production') {
		
            $javascriptTag .= '<script async src="'.$staticSource.'/build/main-2.1.6.js"></script>';
            
        } else {
            
            $javascriptTag .= '<script data-main="'.$staticSource.'/js/main-2.1.6" src="'.$staticSource.'/vendor/requirejs/require-2.1.14.js"></script>';
            
        }
		
		return $javascriptTag;
	
    }
	
}