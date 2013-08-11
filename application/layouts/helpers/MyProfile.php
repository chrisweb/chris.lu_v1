<?php

class Application_Layouts_Helpers_MyProfile extends Zend_View_Helper_Abstract {

	function myProfile()
	{

		$myProfile = '';
	
		$myProfile .= '<div class="widget">';
			$myProfile .= '<h4>'.$this->view->translate('MYPROFILE').'</h4>';
			$myProfile .= '<p>Hi, I\'m Chris Weber (chrisweb) a developer from Luxembourg.</p>';
		$myProfile .= '</div>';
		
		return $myProfile;

	}
	
}