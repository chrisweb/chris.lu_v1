<?php

class Chris_Controller_Action_Helper_Helper_MemoryMeasuring extends Zend_Controller_Action_Helper_Abstract {
    
    /**
     * Starts measuring
     *
     * @return void
     */
    public function startMeasuring() {

        if (function_exists('memory_get_usage')) {

            $bytesStart = memory_get_usage();

            Zend_Registry::set('MemoryUsageStart', $bytesStart);

        } else {

            $msg = 'PHP function "memory_get_usage" doesnt exist';
            throw new Zend_Exception($msg);

        }

    }

    /**
     * Stops measuring
     *
     * @return void
     */
    public function stopMeasuring() {

        if (function_exists('memory_get_usage')) {

            $bytesEnd = memory_get_usage();
            $bytesStart = Zend_Registry::get('MemoryUsageStart');
            $bytesDifference = $bytesEnd-$bytesStart;

            $byteSizeConverter = new Chris_Convert_ByteSizeConverter();

            $memoryDifference = $byteSizeConverter->byteSize($bytesDifference);

            return $memoryDifference;

        } else {

            $msg = 'PHP function "memory_get_usage" doesnt exist';
            throw new Zend_Exception($msg);

        }

    }

    public function getMemoryPeak() {

        if (function_exists('memory_get_peak_usage')) {

            $bytes = memory_get_peak_usage();

            $byteSizeConverter = new Chris_Convert_ByteSizeConverter();

            $peakSize = $byteSizeConverter->byteSize($bytes);

            return $peakSize;

        } else {

            $msg = 'PHP function "memory_get_peak_usage" doesnt exist';
            throw new Zend_Exception($msg);

        }

    }

    public function getMemoryUsage() {

        if (function_exists('memory_get_usage')) {

            $bytes = memory_get_peak_usage();

            $byteSizeConverter = new Chris_Convert_ByteSizeConverter();

            $memory = $byteSizeConverter->byteSize($bytes);

            return $memory;

        } else {

            $msg = 'PHP function "memory_get_usage" doesnt exist';
            throw new Zend_Exception($msg);

        }

    }

}