<?php

class User_Form_ManageUser extends Zend_Form
{

    public function __construct($options = null, $mode, $roleOptions)
	{
	
		parent::__construct($options);

		// disable jquery mobile ajax form submit
		$this->setAttrib('data-ajax', 'false');
	
        $username = new Zend_Form_Element_Text('username');
        $username	->setLabel('AUTH_USERNAME')
                ->setAttrib('placeholder', $this->getView()->translate('AUTH_LOGIN'))
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setRequired(true)
                ->addValidator('StringLength', false, array(2,30));

        $password = new Zend_Form_Element_Password('password');
        $password   ->setLabel('AUTH_PASSWORD')
                    ->setAttrib('placeholder', $this->getView()->translate('AUTH_PASSWORD'))
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->setAttrib('autocomplete', 'off');
					
		if ($mode == 'new') $password->setRequired(true);

        $password_confirm = new Zend_Form_Element_Password('password_confirm');
        $password_confirm   ->setLabel('AUTH_PASSWORD_CONFIRM')
                            ->setAttrib('placeholder', $this->getView()->translate('AUTH_PASSWORD'))
                            ->addFilter('StripTags')
                            ->addFilter('StringTrim')
                            ->setAttrib('autocomplete', 'off')
							->addValidator('Identical', false, array('token' => 'password'))
                            ->setIgnore(true);

        $email = new Zend_Form_Element_Text('email');
        $email	->setLabel('AUTH_EMAIL')
                ->setAttrib('placeholder', $this->getView()->translate('AUTH_EMAIL'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addFilter('StringToLower')
                ->addValidator('EmailAddress')
                ->setRequired(true)
                ->addValidator('StringLength', false, array(6,255));
				
        if (!is_null($roleOptions) && is_array($roleOptions)) {

            $role = new Zend_Form_Element_Select('role');
            $role   ->setLabel('AUTH_ROLE')
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addMultiOptions($roleOptions)
                    ->setRequired(true); // acl system needs roles in db
			
            $this->addElement($role);

        }

        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel('SUBMIT')
                ->setIgnore(true);

		$this->addElements(array($username, $password, $password_confirm, $email, $submit));

    }

}
