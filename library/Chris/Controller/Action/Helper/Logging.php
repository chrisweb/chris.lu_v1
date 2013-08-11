<?php

class Chris_Controller_Action_Helper_Helper_Logging extends Zend_Controller_Action_Helper_Abstract {

    /**
     * @var string
     */
    protected $_directory;

    /**
     * @var string
     */
    protected $_fileName;

    public function  __construct() {
        $_directory = '';
        $_fileName = '';
    }

    /**
     * function to set the default options
     *
     * Example: $this->_helper->logging->setOptions($options);
     *
     * @param array $options
     */
    public function setOptions(array $options = array()) {

        foreach($options as $key => $value) {

            if ($key == 'directory') $this->_directory = $value;
            if ($key == 'filename') $this->_fileName = $value;

        }

    }

    /**
     *
     * @return string
     */
    public function getDirectory() {

        if (empty($this->_directory)) $this->_directory = '/logs/';

        return $this->_directory;

    }

    /**
     *
     * @return string
     */
    public function getFileName() {

        if (empty($this->_fileName)) $this->_fileName = 'application';

        return $this->_fileName;

    }

    /**
     * Perform helper when called as $this->_helper->logging() from an action controller
     *
     * Example: $this->_helper->logging('test');
     *
     */
    public function direct($message, $level = 3) {

        $this->log($message, $level);

    }

    /**
     * Log a message
     *
     * Example: $this->_helper->logging->log('test');
     *
     * @param string $message
     * @param integer $level [EMERG = 0;ALERT = 1;CRIT = 2;ERR = 3;WARN = 4;NOTICE = 5;INFO = 6;DEBUG = 7;
     * @return void
     */
    public function log($message, $level = 3) {

        //Zend_Debug::dump('directory: '.$this->getDirectory());
        //Zend_Debug::dump('filename: '.$this->getFilename());

        $configuration = Zend_Registry::get('Configuration');

        $directory = APPLICATION_PATH.$this->getDirectory();

        if ($configuration->website->logging->status == 1) {

            $message = ' [ IP: '.$_SERVER['REMOTE_ADDR'].' ] - '.$message;
            if (!is_int($level)) $level = strtolower(trim($level));

            $emergStatus = $configuration->website->logging->emerg->status;
            $alertStatus = $configuration->website->logging->alert->status;
            $critStatus = $configuration->website->logging->crit->status;
            $errStatus = $configuration->website->logging->err->status;
            $warnStatus = $configuration->website->logging->warn->status;
            $noticeStatus = $configuration->website->logging->notice->status;
            $infoStatus = $configuration->website->logging->info->status;
            $debugStatus = $configuration->website->logging->debug->status;

            $logStatus = '';

            // check error log level and then log information
            switch($level) {
                case 0:
                case 'emerg':
                case 'emergency':
                    if ($emergStatus) {
                        $logStatus = Zend_Log::EMERG;
                    }
                    break;
                case 1:
                case 'alert':
                    if ($alertStatus) {
                        $logStatus = Zend_Log::ALERT;
                    }
                    break;
                case 2:
                case 'crit':
                case 'critical':
                    if ($critStatus) {
                        $logStatus = Zend_Log::CRIT;
                    }
                    break;
                case 3:
                case 'err':
                case 'error':
                    if ($errStatus) {
                        $logStatus = Zend_Log::ERR;
                    }
                    break;
                case 4:
                case 'warn':
                case 'warning':
                    if ($warnStatus) {
                        $logStatus = Zend_Log::WARN;
                    }
                    break;
                case 5:
                case 'notice':
                    if ($noticeStatus) {
                        $logStatus = Zend_Log::NOTICE;
                    }
                    break;
                case 6:
                case 'info':
                case 'information':
                    if ($infoStatus) {
                        $logStatus = Zend_Log::INFO;
                    }
                    break;
                case 7:
                case 'debug':
                    if ($debugStatus) {
                        $logStatus = Zend_Log::DEBUG;
                    }
                    break;
            }

            if (!empty($logStatus)) {

                $logFilePath = $this->getLogFilePath();
                // create logger instance
                $writer = new Zend_Log_Writer_Stream($logFilePath);
                $logger = new Zend_Log($writer);
                $logger->log($message, $logStatus);

            }


        }

    }

    protected function getLogFilePath() {

        $configuration = Zend_Registry::get('Configuration');
        $directory = APPLICATION_PATH.$this->getDirectory();
        $fileName = $this->getFileName();
        $path = $directory.$fileName.'.log';
        $fileSize = $configuration->website->logging->filesize;

        //Zend_Debug::dump($path);

        // check if logfile exists
        if (file_exists($path)) {

            // check if logfile isnt bigger then configuration maxsize
            if (filesize($path) > $fileSize*1024) {

                $pathOld = $directory.$fileName.date('_d_m_Y_H_i_s', time()).'.log';

                rename($path, $pathOld);

            }
            
        }

        return $path;

    }

}