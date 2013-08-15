<?php

/**
 * 
 */
class Projects_IndexController extends Zend_Controller_Action {

    /**
     * 
     */
    public function init() {

        $chrisContext = $this->_helper->getHelper('ChrisContext');

        $chrisContext->addActionContext('index', 'jquerymobile')
                ->initContext('jquerymobile');

        parent::init();
    }

    /**
     * 
     */
    public function indexAction() {
        
        $bootstrap = $this->getInvokeArg('bootstrap');
        $filescache = $bootstrap->getResource('filescache');
        
        $cachedResults = $filescache->load('github_my_projects');

        if (!$cachedResults) {

            $githubService = new Chris_Service_GitHub();

            $githubService->setPath('/users/chrisweb/repos');

            try {
                $githubResponse = $githubService->query();
            } catch (Exception $exception) {
                $githubResponse = '';
            }

            //Zend_Debug::dump($githubResponse, '$githubService $githubResponse: ');

            require_once(APPLICATION_PATH.'/../library/markdown/Michelf/Markdown.php');

            $markdown = new \Michelf\Markdown();

            $results = array();

            if (is_array($githubResponse) && count($githubResponse) > 0) {

                foreach($githubResponse as $repository) {

                    // only repositories I have created
                    if (!$repository['fork']) {

                        $githubService->setPath('/repos/chrisweb/'.$repository['name'].'/readme');

                        try {
                            $githubReadme = $githubService->query();
                        } catch (Exception $exception) {
                            $githubReadme = '';
                        }

                        //Zend_Debug::dump($githubReadme, '$githubService $githubReadme: ');

                        $readmeContent = '';

                        if (is_array($githubReadme) &&  $githubReadme['encoding'] === 'base64') {

                            $readmeContent = $markdown::defaultTransform(base64_decode($githubReadme['content']));

                        }

                        $results[] = array(
                            'title' => $repository['name'],
                            'readme' => $readmeContent,
                            'url' => $repository['html_url'],
                            'forksCount' => $repository['forks_count'],
                            'watchersCount' => $repository['watchers_count']
                        );

                    }

                }

                $filescache->save($results, 'github_my_projects');
                
                $this->view->githubResults = $results;

            } else {
                
                $this->view->githubResults = '';
                
            }
            
        } else {
            
            $this->view->githubResults = $cachedResults;
            
        }
        
        
        $cachedEventsResults = $filescache->load('github_my_events');

        if (!$cachedEventsResults) {

            Zend_Feed_Reader::setHttpClient(
                // set 5 seconds timeout
                new Zend_Http_Client(null, array('timeout' => 10))
            );

            try {

                $feed = Zend_Feed_Reader::import('https://github.com/chrisweb.atom');

            } catch (Exception $exc) {

                //Zend_Debug::dump($exc->getMessage());
                $feed = array();

            }

            //Zend_Debug::dump($feed);
            
            $events = array();
            
            $events['count'] = $feed->count();
            
            $events['entries'] = array();
            
            foreach ($feed as $entry) {

                $events['entries'][] = array(
                    'date' => $entry->getDateCreated()->toString('F'),
                    'title' => $entry->getTitle()
                );
                        
            }
            
            $filescache->save($events, 'github_my_events');

            $this->view->githubActivityFeed = $events;
            
        } else {
            
            $this->view->githubActivityFeed = $cachedEventsResults;
            
        }
        
    }

}