<?php

class User_Form_LoginForm extends Zend_Form
{

    public function __construct($options = null)
	{
	
		parent::__construct($options);

		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');

        $login = new Zend_Form_Element_Text('username');
        $login	->setLabel('AUTH_USERNAME')
                ->setAttrib('placeholder', $this->getView()->translate('AUTH_USERNAME'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setRequired();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('AUTH_PASSWORD')
                ->addFilter('StripTags')
                ->setAttrib('placeholder', $this->getView()->translate('AUTH_PASSWORD'))
                ->addFilter('StringTrim')
                ->setRequired();

        $remember = new Zend_Form_Element_Checkbox('rememberme');
        $remember	->setLabel('AUTH_REMEMBER')
					->setAttrib('disableHidden', true); // disable hidden element because of conflict with jquery mobile: http://framework.zend.com/issues/browse/ZF-6624?page=com.atlassian.jira.plugin.system.issuetabpanels:all-tabpanel

        $submit = new Zend_Form_Element_Submit('submit');
        $submit	->setLabel('LOG_IN')
                ->setIgnore(true);

        $this->addElements(array($login, $password, $remember, $submit));

    }

}
