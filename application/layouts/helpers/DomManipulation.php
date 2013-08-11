<?php

class Application_Layouts_Helpers_DomManipulation extends Zend_View_Helper_Abstract {

	public function domManipulation($html)
	{

		//http://framework.zend.com/manual/1.12/en/zend.dom.query.html
		//http://php.net/manual/en/domdocument.createelement.php
	
		//Zend_Debug::dump($html);
		
		$front = Zend_Controller_Front::getInstance();
		$moduleName = $front->getRequest()->getModuleName();
		$controllerName = $front->getRequest()->getControllerName();
		$actionName = $front->getRequest()->getActionName();
		
		if ($moduleName === 'homepage' && $controllerName === 'index' && $actionName === 'index'
		|| $moduleName === 'article' && $controllerName === 'index' && $actionName === 'read'
		|| $moduleName === 'article' && $controllerName === 'index' && $actionName === 'tag') {
		
			$manipulatedDocument = '';
			
			try {
				
				$dom = new Zend_Dom_Query($html);
				
				//Zend_Debug::dump($dom, '$dom');
				
				//$documentString = $dom->getDocument();
				
				//Zend_Debug::dump($documentString, '$documentString');
				
				$headerTag = 'h3';
				
				$domResults = $dom->query($headerTag);
				 
				//Zend_Debug::dump(count($domResults), 'count($domResults)');
				
				//Zend_Debug::dump($domResults, '$domResults');
				
				$manipulatedDocument = $this->transformElements($domResults, $headerTag);
				
				$dom = new Zend_Dom_Query($manipulatedDocument);
				
				$headerTag = 'h4';
				
				$domResults = $dom->query($headerTag);
				
				$manipulatedDocument = $this->transformElements($domResults, $headerTag);
				
			} catch(Exception $e) {
			
				$logger = $boostrap->getResource('ApplicationLogging');
				$logger->log($e);
			
			}
			
		} else {
		
			$manipulatedDocument = $html;
		
		}
		
		return $manipulatedDocument;

	}
	
	protected function transformElements($domResults, $headerTag)
	{
	
		$counter = 1;
	
		foreach ($domResults as $domElement) {
			
			// $result is a DOMElement
			
			//Zend_Debug::dump($domElement, '$domElement');
			
			// retrieve the h3 element text content
			$elementTextContent = $domElement->textContent;
			
			$filteredElementTextContent = $this->filterName($elementTextContent);
			
			$linkTagId = $headerTag.'_'.$filteredElementTextContent.'_'.$counter;
			
			$counter++;
			
			// add an id to h3 dom element we found
			$domElement->setAttribute('id', '#'.$linkTagId);
			
			// create a new link element
			$newElement = $domElement->ownerDocument->createElement('a');
			$newElement->setAttribute('class', 'uri_change_anchor');
			$newElement->setAttribute('href', '#'.$linkTagId);
			
			$domElement->appendChild($newElement);

			//Zend_Debug::dump($domElement, '$domElement');
			
		}

		$documentDom = $domResults->getDocument();
		
		//Zend_Debug::dump($domResults->getDocument(), '$domResults->getDocument()');
		
		// unfortunately saveHTML adds a doctype and the html and body tag to any document when saving it
		// i have manipulated an HTML fragment and don't want these tags to get added
		// this replacement removes the unwanted bits from my document
		$manipulatedDocument = preg_replace(array("/^\<\!DOCTYPE.*?<html><body>/si","!</body></html>$!si"), "", $documentDom->saveHTML());
		
		//$manipulatedDocument = $documentDom->saveXML();
		//$manipulatedDocument = $documentDom->saveHTML();
		
		return $manipulatedDocument;
	
	}
	
	protected function filterName($string)
	{
	
		$filter = new Zend_Filter_Alnum();
		
		$filteredString = $filter->filter($string);
		
		return $filteredString;
	
	}
	
}