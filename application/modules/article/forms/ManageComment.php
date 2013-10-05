<?php

class Article_Form_ManageComment extends Zend_Form
{

    /**
     * 
     * @param type $options
     */
    public function __construct($options = null)
	{
	
		parent::__construct($options);
		
		$this->setAttrib('enctype', 'multipart/form-data');

		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');
	
		$email = new Zend_Form_Element_Text('email');
		$email  ->setLabel('EMAIL')
				->addFilter('StringTrim')
				->addFilter('StripTags')
                ->addValidator('EmailAddress')
                ->setRequired(true)
				->addValidator('StringLength', false, array(3, 150));
        
		$name = new Zend_Form_Element_Text('name');
		$name  ->setLabel('NAME')
				->addFilter('StringTrim')
				->addFilter('StripTags')
                ->setRequired(true)
				->addValidator('StringLength', false, array(3, 150));

		$comment = new Zend_Form_Element_Textarea('comment');
		$comment->setLabel('COMMENT')
				->addFilter('StringTrim')
				->setAttrib('cols', '50')
				->setAttrib('rows', '15')
                ->addFilter('HtmlEntities')
                ->setRequired(true)
				->addValidator('StringLength', array(5, 65000));

        $captcha = new Zend_Form_Element_Captcha('captcha', array(
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordLen' => 4,
                'timeout' => 300
            )
        ));
        $captcha->setLabel('CAPTCHA')
                ->setRequired(true);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel('SUBMIT')
                ->setIgnore(true);

		$this->addElements(array($email, $name, $comment, $captcha, $submit));

    }

}
