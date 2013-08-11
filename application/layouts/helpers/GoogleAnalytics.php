<?php

class Application_Layouts_Helpers_GoogleAnalytics extends Zend_View_Helper_Abstract
{
	
    function googleAnalytics()
	{
	
		$front = Zend_Controller_Front::getInstance();
		$controllerName = $front->getRequest()->getControllerName();
		$actionName = $front->getRequest()->getActionName();

        if (APPLICATION_ENV === 'production' && $controllerName !== 'admin' && $actionName !== 'login')
		{

			$output = "<script>
					var _gaq=[['_setAccount','UA-16705563-1'],['_trackPageview']];
					(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
					g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
					s.parentNode.insertBefore(g,s)}(document,'script'));
				</script>";

        } else {

            $output = 'Google Analytics is disabled in non production mode, for admin pages and the login page.';

        }
        
        return $output;
        
    }
    
}