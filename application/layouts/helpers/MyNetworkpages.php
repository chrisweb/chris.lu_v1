<?php

class Application_Layouts_Helpers_MyNetworkpages extends Zend_View_Helper_Abstract {

	function myNetworkpages()
	{

		$myNetworkpages = '';
	
		$myNetworkpages .= '<div class="widget">';
			$myNetworkpages .= '<h4>'.$this->view->translate('MYNETWORKPAGES').'</h4>';
			$myNetworkpages .= '<ul>';
				$myNetworkpages .= '<li><a href="https://github.com/chrisweb" rel="me">github</a></li>';
                $myNetworkpages .= '<li><a href="https://www.facebook.com/webchris" rel="me">facebook</a></li>';
                $myNetworkpages .= '<li><a href="https://twitter.com/chriswwweb" rel="me">twitter</a></li>';
				$myNetworkpages .= '<li><a href="https://plus.google.com/+chrisweber963/posts" rel="me">google+</a></li>';
				$myNetworkpages .= '<li><a href="https://stackoverflow.com/users/656689/chrisweb" rel="me">stackoverflow</a></li>';
				$myNetworkpages .= '<li><a href="https://lu.linkedin.com/in/chrisdotlu" rel="me">linkedin</a></li>';
				$myNetworkpages .= '<li><a href="https://coderwall.com/chrisweb" rel="me">coderwall</a></li>';
                $myNetworkpages .= '<li><a href="https://www.youtube.com/user/chris9630" rel="me">youtube</a></li>';
                $myNetworkpages .= '<li><a href="https://www.jamendo.com/en/list/p89130200/jamchris-selection" rel="me">jamendo</a></li>';
                $myNetworkpages .= '<li><a href="https://play.spotify.com/user/chriswwweb" rel="me">spotify</a></li>';
			$myNetworkpages .= '</ul>';
		$myNetworkpages .= '</div>';
		
		return $myNetworkpages;

	}
	
}