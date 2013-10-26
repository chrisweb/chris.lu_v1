<?php 

class Homepage_ErrorController extends Zend_Controller_Action
{

    public function init()
	{

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('error', 'json')
                    ->initContext();
		
		parent::init();
		
	}
    
    public function errorAction()
    {

        $errors = $this->_getParam('error_handler');
        
        //Zend_Debug::dump($errors);exit;

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
			
				// page not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->errorMessage = 'PAGE_NOT_FOUND';
                break;
				
            default:
			
				if ($errors->exception instanceof My_Exception) {
					// do something special
				}

				// application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->errorMessage = 'APPLICATION_ERROR';
                
				if (APPLICATION_ENV !== 'production') {
                    
                    $this->view->exceptionMessage = $errors->exception->getMessage();
                    $this->view->exceptionFile = $errors->exception->getFile();
                    $this->view->exceptionLine = $errors->exception->getLine();
                    $this->view->exceptionTrace = $errors->exception->getTrace();
                    $this->view->type = $errors->type;
                    $this->view->request = $errors->request;
                    
                }
                
                break;
				
        }
		
		$moduleName = $errors->request->getModuleName();
		$controllerName = $errors->request->getControllerName();
		$actionName = $errors->request->getActionName();
		$exception = $errors->exception;
		
		$errorMessage = $errors->exception->getMessage();
    	$errorInFile = $errors->exception->getFile();
    	$errorAtLine = $errors->exception->getLine();
		
		$bootstrap = $this->getInvokeArg('bootstrap');
		$logger = $bootstrap->getResource('ApplicationLogging');
		
		$delemiter = '/*****************************/';
		
		//$logger->log($moduleName.' - '.$controllerName.' - '.$actionName.' :: '.$errorMessage."\r\n".$exception."\r\n".' IN FILE: '.$errorInFile.' @LINE: '.$errorAtLine."\r\n".$delemiter."\r\n", Zend_Log::ERR);
 
        $metaData = array('custom data' =>
            array(
                'moduleName' => $moduleName,
                'controllerName' => $controllerName,
                'actionName' => $actionName,
                'errorMessage' => $errorMessage,
                'errorInFile' => $errorInFile,
                'errorAtLine' => $errorAtLine
            )
        );

        require_once(APPLICATION_PATH.'/../library/Bugsnag/lib/bugsnag.php');
        
        Bugsnag::setMetaData($metaData);
        Bugsnag::notifyException($exception);
        
        // Clear previous content
        $this->getResponse()->clearBody();
		
    }

}