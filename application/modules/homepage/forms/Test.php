<?php

class Homepage_Form_Test extends Zend_Form
{

    public function __construct($options = null)
	{
	
		parent::__construct($options);
		
		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');
	
		$title = new Zend_Form_Element_Text('title');
		$title  ->setLabel('TITLE')
				->addFilter('StringTrim')
				->addFilter('StripTags');

		$header = new Zend_Form_Element_Textarea('header');
		$header ->setLabel('HEADER')
				->addFilter('StringTrim')
				->setAttrib('cols', '50')
				->setAttrib('rows', '5');

		$body = new Zend_Form_Element_Textarea('body');
		$body   ->setLabel('BODY')
				->addFilter('StringTrim')
				->setAttrib('cols', '50')
				->setAttrib('rows', '5');
				
		$tags = new Zend_Form_Element_Text('tags');
		$tags->setLabel('TAGS')
				->addFilter('StringTrim')
				->addFilter('StripTags');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel('SUBMIT')
                ->setIgnore(true);

		$this->addElements(array($title, $header, $body, $tags, $submit));

    }

}
