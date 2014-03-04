<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
              realpath(dirname(__FILE__) . '/../application'));
 
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

// check for apc support
define('APC_SUPPORT', extension_loaded('apc') && ini_get('apc.enabled'));

// Typically, you will also want to add your library/ directory
// to the include_path, particularly if it contains your ZF installed
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(dirname(__FILE__)) . '/library',
    get_include_path(),
)));
 
/** Zend_Application */
require_once 'Zend/Application.php';

// create a zend autoloader instance so that all the classes can be loaded automatically
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();

// put the autoloader in registry for boostrap and other modules
Zend_Registry::set('Autoloader', $autoloader);

// setup some options
$cacheDirectory = APPLICATION_PATH.'/caches/';
$configurationPath = APPLICATION_PATH.'/configs/application.ini';
$configurationName = 'applicationconfiguration';
$cacheLifetime = 2678400;

// check if cache directory exists if not create it
if (!is_dir($cacheDirectory)) mkdir($cacheDirectory, 0755);

if (APC_SUPPORT) {
    
    // if no lifetime is defined default will be 3600
    $frontendOptions = array(
        'automatic_serialization' => true,
        'lifetime' => $cacheLifetime
    );
    
    $backendOptions = array();
    
    $configurationCache = Zend_Cache::factory('Core', 'Apc', $frontendOptions, $backendOptions);
    
} else {
    
    // if no lifetime is defined default will be 3600
    $frontendOptions = array(
        'master_files' => array($configurationPath),
        'automatic_serialization' => true,
        'lifetime' => $cacheLifetime
    );
    
    $backendOptions = array('cache_dir' => $cacheDirectory);
    
    $configurationCache = Zend_Cache::factory('File', 'File', $frontendOptions, $backendOptions);
    
}

// if configuration not in cache load it and put it into cache
if (!$configuration = $configurationCache->load($configurationName)) {
    $configuration = new Zend_Config_Ini($configurationPath, APPLICATION_ENV);
    $configurationCache->save($configuration, $configurationName);
}

// put configuration in registry for boostrap and other modules
Zend_Registry::set('ApplicationConfiguration', $configuration);
 
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

try {

	$application->bootstrap()
				->run();
				
} catch (Exception $e) {

    if (APPLICATION_ENV === 'production') {
        
        die();
        
    } else {
        
        die($e);
        
    }
	

}