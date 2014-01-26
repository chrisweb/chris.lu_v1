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
        
        $myNetworkpages .= '<div class="g-person" data-width="240" data-href="//plus.google.com/115936397667079055215" data-layout="landscape" data-rel="author"></div>';
        $myNetworkpages .= '<br><br>';
        
        $myNetworkpages .= '<div class="g-ytsubscribe" data-channelid="UC_Y3-cPBBSiyRfN63CmzS1g" data-layout="full" data-count="default" data-onytevent="onYtEvent"></div>';
        $myNetworkpages .= '<br><br>';
        
        $myNetworkpages .= '<a href="https://twitter.com/chriswwweb" class="twitter-follow-button" data-show-count="true" data-lang="en" data-size="large">Follow @chriswwweb</a>';
        $myNetworkpages .= '<br><br>';
        
        $myNetworkpages .= '<a href="https://stackexchange.com/users/331783">';
        $myNetworkpages .= '<img src="https://stackexchange.com/users/flair/331783.png?theme=clean" width="208" height="58" alt="profile for chrisweb on Stack Exchange, a network of free, community-driven Q&A sites" title="profile for chrisweb on Stack Exchange, a network of free, community-driven Q&A sites">';
        $myNetworkpages .= '</a>';
        $myNetworkpages .= '<br><br>';
        
        $myNetworkpages .= '<iframe src="//www.facebook.com/plugins/follow.php?href=http%3A%2F%2Fwww.facebook.com%2Fwebchris&amp;width&amp;height=80&amp;colorscheme=light&amp;layout=standard&amp;show_faces=true&amp;appId=424957510901747" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:80px;" allowTransparency="true"></iframe>';
        $myNetworkpages .= '<br><br>';
        
		return $myNetworkpages;

	}
	
}