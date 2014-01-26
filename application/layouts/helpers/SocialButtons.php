<?php

class Application_Layouts_Helpers_SocialButtons extends Zend_View_Helper_Abstract
{

    public function socialButtons()
	{

		$socialButtons = '';
		
		$front = Zend_Controller_Front::getInstance();
		$moduleName = $front->getRequest()->getModuleName();
		$controllerName = $front->getRequest()->getControllerName();
		$actionName = $front->getRequest()->getActionName();
		
		if ($moduleName == 'article' && $controllerName == 'index' && $actionName == 'read') {
		
            if (is_array($this->view->article)) {

                $socialButtons .= '<div class="widget">';
                    $socialButtons .= '<h4>'.$this->view->translate('SOCIAL_BUTTONS').'</h4>';

                    $id = $front->getRequest()->getParam('id');

                    // facebook
                    // https://developers.facebook.com/docs/reference/plugins/like/
                    $socialButtons .= '<div id="facebook" style="width: 33%; float: left;">';
                    $socialButtons .= '<div class="fb-like" data-href="https://chris.lu/article/read/'.$id.'" data-send="true" data-layout="box_count" data-width="85" data-show-faces="false" data-font="verdana"></div>';
                    $socialButtons .= '</div>';

                    // google +
                    // https://developers.google.com/+/plugins/share/
                    // https://developers.google.com/+/plugins/snippet/?hl=en
                    $socialButtons .= '<!-- Place this tag where you want the share button to render. -->';
                    $socialButtons .= '<div id="google+" style="width: 33%; float: left;">';
                    $socialButtons .= '<div class="g-plus" data-action="share" data-annotation="vertical-bubble" data-height="60" data-href="https://chris.lu/article/read/'.$id.'"></div>';
                    $socialButtons .= '</div>';

                    // twitter
                    $hashTagsAttribute = '';

                    if (array_key_exists('tags', $this->view->article) && count($this->view->article['tags']) > 0) {
                        $tagsArray = array();
                        foreach($this->view->article['tags'] as $tag) {
                            if (!empty($tag['name'])) {
                                $tagsArray[] = $this->view->escape($tag['name']);
                            }
                        }
                        $tags = implode(', ', $tagsArray);
                        if (count($tags) > 0) $hashTagsAttribute = 'data-hashtags="'.$tags.'"';
                    }

                    $socialButtons .= '<div id="twitter" style="width: 33%; float: left;">';
                    $socialButtons .= '<a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical" data-lang="en" data-url="https://chris.lu/article/read/'.$id.'" data-text="chris.lu :: '.$this->view->escape($this->view->article['title']).'" data-via="chriswwweb" '.$hashTagsAttribute.'>Tweet</a>';
                    $socialButtons .= '</div>';
                    
                    // javascript is now loaded inside of application.js
                    //$socialButtons .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';

                    $socialButtons .= '<div class="clearfix"></div>';

                $socialButtons .= '</div>';
                
            } else {
                
                $socialButtons = '';
                
            }
		
		}
	
		return $socialButtons;

    }
	
}