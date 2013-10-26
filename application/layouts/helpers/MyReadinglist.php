<?php

class Application_Layouts_Helpers_MyReadinglist extends Zend_View_Helper_Abstract {

	function myReadinglist()
	{

		$readinglistModel = new Readinglist_Model_MongoDB_Readinglist();
		
		$keys = array('title', 'url', 'favicon', 'publish_date');
		
		$cursor = $readinglistModel->getList(array(), $keys);
		
		$myReadinglist = '';
		
		if (!is_null($cursor)) {
		
			$cursor->limit(10)->sort(array('publish_date' => -1));
			
			$myReadinglist .= '<div class="widget">';
			
				$myReadinglist .= '<h4>'.$this->view->translate('MYREADINGLIST').'</h4>';
			
				$myReadinglist .= '<ul>';
				
				foreach ($cursor as $readinglist) {

					//Zend_Debug::dump(APPLICATION_PATH.'/../public'.$readinglist['favicon']);
					//Zend_Debug::dump(is_readable(APPLICATION_PATH.'/../public'.$readinglist['favicon']));
					
					//Zend_Debug::dump($readinglist['favicon']);
					
					//Zend_Debug::dump(APPLICATION_PATH);
					
					//Zend_Debug::dump($readinglist['publish_date']);
					
					if (array_key_exists('favicon', $readinglist) && !empty($readinglist['favicon']) && is_readable(APPLICATION_PATH.'/../public'.$readinglist['favicon'])) {
					
						$fileContent = file_get_contents(APPLICATION_PATH.'/../public'.$readinglist['favicon']);
				
						$myReadinglist .= '<li style="list-style-type: none !important;"><img src="data:image/ico;base64,'.base64_encode($fileContent).'" class="readinglist_icon"><a href="'.$this->view->escape($readinglist['url']).'" rel="external">'.$this->view->escape($readinglist['title']).'</a></li>';

					} else {
					
						$myReadinglist .= '<li><a href="'.$this->view->escape($readinglist['url']).'" rel="external">'.$this->view->escape($readinglist['title']).'</a></li>';
					
					}
					
				}
				
				$myReadinglist .= '</ul>';
			
			$myReadinglist .= '</div><div class="clearfix"></div>';
			
		}
		
		return $myReadinglist;

	}
	
}
