<?php

class Chris_Form extends Zend_Form
{

	public function __construct($options = null, $useHash = false, $namespace = null)
	{
	
		parent::__construct($options);
		
		$this->setAttrib('accept-charset', 'UTF-8');
		
		$this->addPrefixPath('Chris_Form_Decorator', 'Chris/Form/Decorator/', 'decorator');
		
		if ($useHash) {

            $hashTimeout = '3600';
            $salt = sha1(time().rand(1, 1000));

            // if form has a namespace value, usr it for the hash name too, avoid conflicts on pages that have more then one form
            if ($namespace === null) $namespace = 'chris';

			$hash = new Zend_Form_Element_Hash('hash_'.$namespace, 'no_csrf', array('salt' => $salt));

            $hash   ->clearDecorators()
                    ->setIgnore(true)
                    ->setDecorators(array('ViewHelper'))
                    ->setTimeout($hashTimeout)
                    ->getSession()->setExpirationHops(1, null, true);

            $this->addElement($hash);
		
		}
		
	}
		
	public function render(Zend_View_Interface $view = NULL)
	{

		foreach ($this->getElements() as $element) {
		
			//Zend_Debug::dump($element);
		
			if ($element instanceof Zend_Form_Element_Text) {
			
				//Zend_Debug::dump($element->getId());
				//Zend_Debug::dump($element->getLabel());
			
				$element->setAttrib('placeholder', $element->getLabel());
				
			}
			
		}
		
		$content = parent::render($view);
		
		return $content;
	
	}

}