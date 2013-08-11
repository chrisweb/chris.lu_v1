<?php

class Bookmark_Form_ManageBookmark extends Zend_Form
{

    public function __construct($options = null)
	{
	
		parent::__construct($options);

		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');
	
		$title = new Zend_Form_Element_Text('title');
		$title  ->setLabel('TITLE')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 100));

		$url = new Zend_Form_Element_Text('url');
		$url ->setLabel('URL')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 300));
					
		$tags = new Zend_Form_Element_Text('tags');
		$tags  ->setLabel('TAGS')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 300));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel('SUBMIT')
                ->setIgnore(true);

		$this->addElements(array($title, $url, $tags, $submit));

    }

}
