<?php

class Projects_IndexController extends Zend_Controller_Action {

    public function init() {

        $chrisContext = $this->_helper->getHelper('ChrisContext');

        $chrisContext->addActionContext('index', 'jquerymobile')
                ->initContext('jquerymobile');

        parent::init();
    }

    public function indexAction() {

        
        $githubService = new Chris_Service_GitHub();
        
        $githubService->setPath('/users/chrisweb/repos');
        
        $results = $githubService->query();
        
        //Zend_Debug::dump($results, '$githubService $results: ');

        foreach ($results as $key => $value) {
            
            Zend_Debug::dump($key, '$key: ');
            Zend_Debug::dump($value, '$value: ');
            
        }
        
        
        
        $feed = Zend_Feed_Reader::import('https://github.com/chrisweb.atom');

        //Zend_Debug::dump($feed);
        
        Zend_Debug::dump('Last ' . $feed->count() . ' GitHub actions: ');

        foreach ($feed as $entry) {

            Zend_Debug::dump('('.$entry->getDateCreated()->toString('F').') '.$entry->getTitle());
            
        }
        
        
    }

}