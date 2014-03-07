<?php

/**
 * 
 */
class Playlist_IndexController extends Zend_Controller_Action {

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
        
        $applicationConfiguration = $bootstrap->getResource('ApplicationConfiguration');
        
        $cache = $bootstrap->getResource('CacheSwitch');
        
        $cache->setOption('lifetime', $applicationConfiguration->cache->content->lifetime);
        
        $cachedResults = $cache->load('youtube_playlists');
        
        /**
         * work in progress ;)
         */
        
        //$cachedResults = false;

        if (!$cachedResults) {

            $youtubeService = new Chris_Service_Youtube();
            
            // load the list of playlist dynamically
            //https://www.googleapis.com/youtube/v3/playlists
            
            /*$playlistsIds = array();
            $playlistsIds[] = 'PLMalxPH8bteV09fywVS_DM8QoByBj2o8w';
            $playlistsIds[] = 'PLMalxPH8bteWmy3bK0qO092C52iJsE62I';
            $playlistsIds[] = 'PLMalxPH8bteXt0WqVkRvs1bGFOxD_PzBb';
            $playlistsIds[] = 'PLMalxPH8bteW7zzcmIvxxpqe2TqlxIYGB';
            $playlistsIds[] = 'PLMalxPH8bteUp4OW_PIFNizLzVtKINdul';
            $playlistsIds[] = 'PLMalxPH8bteVFD_1Bq1ZnHhZ7YVxNAcFf';
            $playlistsIds[] = 'PLMalxPH8bteUMLiXK1OyAT8VvHv0pW7dm';
            $playlistsIds[] = 'PLMalxPH8bteX1vDZI3yQSEWiD2eKIIVY9';
            $playlistsIds[] = 'PLMalxPH8bteUJHvOWddGb6qV257RlG65y';
            $playlistsIds[] = 'PLMalxPH8bteXBpFD2j3VKQiAqlyRHc2s0';

            $youtubeService->setPath('/playlists?part=id,snippet,status&key=AIzaSyBzEeJ0VPrk9CkFQEfAy6rkNlcW1Z8ALog&id='.implode(',', $playlistsIds).'&maxResults=50');*/
            
            $playlistConfiguration = Zend_Registry::get('PlaylistConfiguration');

            $youtubeService->setPath('/playlists?part=id,snippet,status&key=AIzaSyBzEeJ0VPrk9CkFQEfAy6rkNlcW1Z8ALog&channelId='.$playlistConfiguration->youtube->channel->id.'&maxResults=50');
            
            try {
                
                $youtubeResponse = $youtubeService->query();
                
            } catch (Exception $exception) {
                
                //Zend_Debug::dump($exception, '$youtubeService $exception: ');
                
                $youtubeResponse = '';
            }

            //Zend_Debug::dump($youtubeResponse, '$youtubeService $youtubeResponse: ');
            //exit;

            $results = array();

            if (is_array($youtubeResponse) && count($youtubeResponse) > 0) {

                foreach($youtubeResponse as $youtubeFieldName => $youtubeFieldValue) {
                    
                    //Zend_Debug::dump($youtubeFieldName, '$youtubeFieldName: ');
                    //Zend_Debug::dump($youtubeFieldValue, '$youtubeFieldValue: ');

                    // only repositories I have created
                    if ($youtubeFieldName === 'items' && is_array($youtubeFieldValue) && count($youtubeFieldValue) > 0) {

                        foreach($youtubeFieldValue as $youtubePlaylist) {
                            
                            //Zend_Debug::dump($youtubePlaylist, '$youtubePlaylist: ');
                            
                            $results[] = $youtubePlaylist;
                            
                        }

                    }

                }

                $cache->save($results, 'youtube_playlists');
                
                $this->view->youtubeResults = $results;

            } else {
                
                $this->view->youtubeResults = '';
                
            }
            
        } else {
            
            $this->view->youtubeResults = $cachedResults;
            
        }
        
    }

}