<?php

class Chris_Form_Decorator_Label extends Zend_Form_Decorator_Label {

    public function __construct($options = null) {

        parent::__construct($options);

        $this	->setOption('escape', true)
                ->setOption('requiredSuffix', ' *');

    }
	
}
