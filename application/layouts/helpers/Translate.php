<?php

class Application_Layouts_Helpers_Translate extends Zend_View_Helper_Abstract {
	
    function translate($data) {
    	
    	$translate = Zend_Registry::get('Translate');
		
        if (is_object($translate)) {

            return $translate->_($data);

        } else {

            return $data;

        }
        
    }
    
}
