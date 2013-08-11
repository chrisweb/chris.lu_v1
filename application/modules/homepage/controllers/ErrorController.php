<?php 

class Homepage_ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {

        $errors = $this->_getParam('error_handler');
		
		//Zend_Debug::dump($errors->type);
 
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
				if (APPLICATION_ENV != 'production') $this->view->exception = $errors->exception;
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
		
		$logger->log($moduleName.' - '.$controllerName.' - '.$actionName.' :: '.$exception."\r\n".$errorInFile."\r\n".$delemiter."\r\n", Zend_Log::ERR);
 
        // Clear previous content
        $this->getResponse()->clearBody();
		
    }

}