<?php

class Chris_Form_SubForm extends Zend_Form_SubForm {
    
    public function __construct($options = null) {
        
        parent::__construct($options);
		
		$this->addPrefixPath('Chris_Form_Decorator', 'Chris/Form/Decorator/', 'decorator');
        
    }
    
}