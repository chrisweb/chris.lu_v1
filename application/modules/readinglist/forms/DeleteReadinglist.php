<?php

class Readinglist_Form_DeleteReadinglist extends Zend_Form
{

    public function __construct($options = null)
	{
	
		parent::__construct($options);
	
		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');

		$yes = new Zend_Form_Element_Submit('yes');
        $yes->setLabel('YES');

        $no = new Zend_Form_Element_Submit('no');
        $no->setLabel('NO');

		$this->addElements(array($yes, $no));

    }

}
