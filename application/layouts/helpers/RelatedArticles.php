<?php

class Application_Layouts_Helpers_RelatedArticles extends Zend_View_Helper_Abstract {

	function relatedArticles()
	{

		$relatedArticles = '';
	
		if (array_key_exists('relatedArticles', $this->view->article)) {
	
			$relatedArticles .= '<div class="widget">';
				$relatedArticles .= '<h4>'.$this->view->translate('RELATED_ARTICLES').'</h4>';
				$relatedArticles .= '<ul>';
					foreach($this->view->article['relatedArticles'] as $relatedArticle) {
						$relatedArticles .= '<li><a href="'.$this->view->url(array('id' => $relatedArticle['_id']), 'articleindexread').'">'.$this->view->escape($relatedArticle['title']).'</a></li>';
					}
				$relatedArticles .= '</ul>';
			$relatedArticles .= '</div>';
		
		}
		
		return $relatedArticles;

	}
	
}