<?php

/**
 * DbProfiler plugin
 *
 * @author weber chris
 * @version 1.1
 * @license gpl v3
 *
 */
class Chris_Controller_Plugin_MysqlDatabaseProfiler extends Zend_Controller_Plugin_Abstract {

    /**
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request) {

        // DATABASE QUERIES PROFILER
        $configuration = Zend_Registry::get('Configuration');
        $profilerAction = $configuration->resources->db->params->profiler;
        
        //Zend_Debug::dump($profilerAction, '$profilerAction');
        //Zend_Debug::dump(IS_XHR, 'IS_XHR');

        if ($profilerAction && !IS_XHR) {

            $profiler = Zend_Registry::get('Profiler');

            // add value here (seconds) to ouput only queries that need more time then xx seconds
            $profiler->setFilterElapsedSecs(null);

            $totalTime    = $profiler->getTotalElapsedSecs();
            $queryCount   = $profiler->getTotalNumQueries();
            $longestTime  = 0;
            $longestQuery = null;

            $profilerContent = '';

            // calculate max query times
            if ($queryCount > 0) {

                // ouput quries data
                $profilerContent .= '<div style="background-color: #ffffff; color: #000000; border:1px solid #7b7b7b; padding: 20px;">';
                $profilerContent .= '<strong>DB Profiler: </strong><br /><br />';
                $profilerContent .= 'Executed '.$queryCount.' queries in <span style="color: #ff0000;">'.$totalTime.' seconds</span><br />';
                $profilerContent .= 'Average query length: <span style="color: #ff0000;">'.$totalTime / $queryCount.' seconds</span><br />';
                $profilerContent .= 'Queries per second: <span style="color: #ff0000;">'.$queryCount / $totalTime.'</span><br /><br />';
                $profilerContent .= '<strong>DB Queries listing:</strong><br />';
                $profilerContent .= '<ol>';

                foreach ($profiler->getQueryProfiles() as $query) {

                    if ($query->getElapsedSecs() > $longestTime) {

                        $longestTime  = $query->getElapsedSecs();
                        $longestQuery = $query->getQuery();

                    }

                    $profilerContent .= '<li>'.$query->getQuery().' -> Length: <span style="color: #ff0000;">'.$query->getElapsedSecs().' seconds</span></li>';

                }

                $profilerContent .= '</ol><br />';
                $profilerContent .= '<strong>Longest Query (needs perhaps optimization): </strong><br /><br />';
                $profilerContent .= 'Longest query length: <span style="color: #ff0000;">'.$longestTime.' seconds</span><br />';
                $profilerContent .= 'Longest query: '.$longestQuery.'<br />';
                $profilerContent .= '</div>';

            }
            
            //Zend_Debug::dump($profilerContent, '$profilerContent');
            
            $frontControllerInstance = Zend_Controller_Front::getInstance();
            $response = $frontControllerInstance->getResponse();
            $response->appendBody($profilerContent, 'profiler');

        }

    }

}
