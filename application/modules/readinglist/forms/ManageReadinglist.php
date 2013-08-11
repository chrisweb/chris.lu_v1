<?php

class Readinglist_Form_ManageReadinglist extends Chris_Form
{

    public function __construct($options = null, array $tags = array())
	{
	
		parent::__construct($options);

		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');
		
		$articleUrl = new Zend_Form_Element_Text('url');
		$articleUrl	->setLabel('ARTICLE_URL')
					->addFilter('StringTrim')
					->addFilter('StripTags')
					->addValidator('StringLength', false, array(3, 300))
					->setRequired(true);
	
		$title = new Zend_Form_Element_Text('title');
		$title  ->setLabel('TITLE')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 100))
				->setRequired(true);

		$headline = new Zend_Form_Element_Textarea('headline');
		$headline	->setLabel('HEADLINE')
					->addFilter('StringTrim')
					->setAttrib('cols', '50')
					->setAttrib('rows', '5')
					->addValidator('StringLength', array(5, 65000));
				
		$imageUrl = new Zend_Form_Element_Text('imageUrl');
		$imageUrl	->setLabel('IMAGE_URL')
					->addFilter('StringTrim')
					->addFilter('StripTags')
					->addValidator('StringLength', false, array(3, 250));
				
		$imageAlt = new Zend_Form_Element_Text('imageAlt');
		$imageAlt	->setLabel('IMAGE_ALT')
					->addFilter('StringTrim')
					->addFilter('StripTags')
					->addValidator('StringLength', false, array(3, 100));
				
		$favicon = new Zend_Form_Element_Text('favicon');
		$favicon	->setLabel('FAVICON')
					->addFilter('StringTrim')
					->addFilter('StripTags')
					->addValidator('StringLength', false, array(3, 250));
					
		$domain = new Zend_Form_Element_Text('domain');
		$domain	->setLabel('DOMAIN')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 100))
				->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel('SUBMIT')
                ->setIgnore(true);

		$this->addElements(array($articleUrl, $title, $headline, $imageUrl, $imageAlt, $favicon, $domain, $submit));

    }

}
