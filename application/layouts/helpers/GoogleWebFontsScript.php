<?php

class Application_Layouts_Helpers_GoogleWebFontsScript extends Zend_View_Helper_Abstract
{

    public function googleWebFontsScript()
	{

		$this->view->headScript()->captureStart(); ?>
		
			WebFontConfig = {
				google: { families: [ 'Electrolize::latin', 'Ubuntu:400,400italic:latin,latin-ext', 'Droid+Sans+Mono::latin' ] }
			};
			(function() {
				var wf = document.createElement('script');
				wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
				wf.type = 'text/javascript';
				wf.async = 'true';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(wf, s);
			})();
		
		<?php $this->view->headScript()->captureEnd();

    }
	
}