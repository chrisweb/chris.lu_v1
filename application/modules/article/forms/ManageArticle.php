<?php

class Article_Form_ManageArticle extends Zend_Form
{

    public function __construct($options = null)
	{
	
		parent::__construct($options);
		
		$this->setAttrib('enctype', 'multipart/form-data');

		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');
	
		$title = new Zend_Form_Element_Text('title');
		$title  ->setLabel('TITLE')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 150));

		$header = new Zend_Form_Element_Textarea('header');
		$header ->setLabel('HEADER')
				->addFilter('StringTrim')
				->setAttrib('cols', '50')
				->setAttrib('rows', '5')
				->addValidator('StringLength', array(5, 65000));

		$body = new Zend_Form_Element_Textarea('body');
		$body   ->setLabel('BODY')
				->addFilter('StringTrim')
				->setAttrib('cols', '50')
				->setAttrib('rows', '5')
				->addValidator('StringLength', array(5, 65000));
				
		$image = new Zend_Form_Element_File('image');
		$image	->setLabel('UPLOAD')
				->setDestination(APPLICATION_PATH.'/../public/upload/images/')
				->addValidator('Count', false, 1)
				->addValidator('Size', false, 1024000)
				->addValidator('Extension', false, 'jpg,png,gif');
				
		$image_alt = new Zend_Form_Element_Text('image_alt');
		$image_alt  ->setLabel('IMAGE_ALT')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 100));

        $dateStart = new Zend_Dojo_Form_Element_DateTextBox('date_start');
        $dateStart  ->setLabel('PUBLICATION_DATE_START');

        $dateEnd = new Zend_Dojo_Form_Element_DateTextBox('date_end');
        $dateEnd    ->setLabel('PUBLICATION_DATE_END');
								
		$tags = new Zend_Form_Element_Text('tags');
		$tags->setLabel('TAGS')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 300));
		
		$relatedTag = new Zend_Form_Element_Text('relatedTag');
		$relatedTag->setLabel('RELATED_TAG')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addValidator('StringLength', false, array(3, 300));

        $status = new Zend_Form_Element_Select('status');
        $status	->setLabel('STATUS')
                ->addMultiOptions(array('2' => 'ACTIV', '1' => 'INACTIV'));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel('SUBMIT')
                ->setIgnore(true);

		$this->addElements(array($title, $header, $body, $image, $image_alt, $dateStart, $dateEnd, $tags, $relatedTag, $status, $submit));

    }

}
