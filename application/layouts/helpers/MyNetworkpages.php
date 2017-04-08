<?php

class Application_Layouts_Helpers_MyNetworkpages extends Zend_View_Helper_Abstract {

    function myNetworkpages() {

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

        $myNetworkpages .= '<div class="github-card" data-github="chrisweb" data-width="300" data-height="" data-theme="default"></div>';
        $myNetworkpages .= '<script src="//cdn.jsdelivr.net/github-cards/latest/widget.js"></script>';
        $myNetworkpages .= '<br><br>';
        
        $myNetworkpages .= '<a href="https://twitter.com/chriswwweb" class="twitter-follow-button" data-show-count="true" data-lang="en" data-size="large">Follow @chriswwweb</a>';
        $myNetworkpages .= '<br><br>';

        $myNetworkpages .= '<div class="g-person" data-width="240" data-href="//plus.google.com/115936397667079055215" data-layout="landscape" data-rel="author"></div>';
        $myNetworkpages .= '<br><br>';

        $myNetworkpages .= '<div class="g-ytsubscribe" data-channelid="UC_Y3-cPBBSiyRfN63CmzS1g" data-layout="full" data-count="default" data-onytevent="onYtEvent"></div>';
        $myNetworkpages .= '<br><br>';

        $myNetworkpages .= '<a href="https://stackoverflow.com/users/656689/chrisweb">';
        $myNetworkpages .= '<img src="https://stackoverflow.com/users/flair/656689.png" width="208" height="58" alt="profile for chrisweb at Stack Overflow, Q&amp;A for professional and enthusiast programmers" title="profile for chrisweb at Stack Overflow, Q&amp;A for professional and enthusiast programmers">';

        $myNetworkpages .= '</a>';
        $myNetworkpages .= '<br><br>';

        $myNetworkpages .= '<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>';
        $myNetworkpages .= '<script type="IN/MemberProfile" data-id="https://www.linkedin.com/in/chrisdotlu" data-format="inline" data-related="false" data-width="82%"></script>';
        $myNetworkpages .= '<br><br>';

        $myNetworkpages .= '<iframe src="https://www.facebook.com/plugins/follow.php?href=https%3A%2F%2Fwww.facebook.com%2Fwebchris&width=200px&height=80&layout=standard&size=large&show_faces=true&appId=424957510901747" width="200px" height="80" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>';

        return $myNetworkpages;

    }
	
}
