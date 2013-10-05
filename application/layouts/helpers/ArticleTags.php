<?php

class Application_Layouts_Helpers_ArticleTags extends Zend_View_Helper_Abstract {

	function articleTags()
	{

		$articleTags = '';
		
		if (is_array($this->view->article) && array_key_exists('tags', $this->view->article) && count($this->view->article['tags']) > 0) {
	
			$articleTags .= '<div class="widget">';
				$articleTags .= '<h4>'.$this->view->translate('TAGS').'</h4>';

				foreach($this->view->article['tags'] as $tag) {
				
					if (!empty($tag['name'])) {
				
						$articleTags .= '<a class="btn btn-primary" href="'.$this->view->url(array('id' => $tag['id']), 'articleindextag').'">'.$this->view->escape($tag['name']).'</a>';
					
					}
					
				}

			$articleTags .= '</div>';
		
		}
		
		return $articleTags;

	}
	
}