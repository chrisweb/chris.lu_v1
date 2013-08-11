<?php

class Application_Layouts_Helpers_MyNetworkpages extends Zend_View_Helper_Abstract {

	function myNetworkpages()
	{

		$myNetworkpages = '';
	
		$myNetworkpages .= '<div class="widget">';
			$myNetworkpages .= '<h4>'.$this->view->translate('MYNETWORKPAGES').'</h4>';
			$myNetworkpages .= '<ul>';
				$myNetworkpages .= '<li><a href="https://github.com/chrisweb" rel="me">github</a></li>';
				$myNetworkpages .= '<li><a href="https://twitter.com/chriswwweb" rel="me">twitter</a></li>';
				$myNetworkpages .= '<li><a href="https://plus.google.com/115936397667079055215/posts" rel="me">google+</a></li>';
				$myNetworkpages .= '<li><a href="http://stackoverflow.com/users/656689/chrisweb" rel="me">stackoverflow</a></li>';
				$myNetworkpages .= '<li><a href="http://lu.linkedin.com/in/chrisdotlu" rel="me">linkedin</a></li>';
				$myNetworkpages .= '<li><a href="http://coderwall.com/chrisweb" rel="me">coderwall</a></li>';
			$myNetworkpages .= '</ul>';
		$myNetworkpages .= '</div>';
		
		return $myNetworkpages;

	}
	
}