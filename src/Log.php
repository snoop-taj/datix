<?php

namespace Datix;

/**
 * Log Class
 * 
 */

class Log
{
        /**
         * Log folder name
         * 
         * @var string
         */
        const FILE_LOG_FOLDER = "/tmp/";
        
        /**
         * Log file name
         * 
         * @var string
         */
        const FILE_LOG_NAME = "log.log";
        
        /**
         * An instance of Log class
         * 
         * @var Log 
         */
        private static $_instance = null;
        
        /**
         * File path
         * 
         * @var string 
         */
        private $file;
        
        /**
         * Log construct create file
         * 
         * @param string $fileName
         */
        public function __construct($fileName = '') {
            
                $directory = dirname(__FILE__). self::FILE_LOG_FOLDER;
                
                if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);   
                } 
                
                if (empty($fileName)) {
                    $file = $directory.'/'.date('d.m.Y').'_'. self::FILE_LOG_NAME;
                } else {
                    $file = $directory.'/'.date('d.m.Y').'_'. $fileName;
                }
                
                                
                if(!file_exists($file)) {
			file_put_contents($file, '');
		}
                
		$this->file = $file;
        }
        
        /**
         * Return an instance of Log class
         * 
         * @param string $fileName
         * @return Log
         */
        public static function instance($fileName='') { 
                
                if (!isset(self::$_instance)) { 
                  self::$_instance = new Log($fileName); 
                } 
                
                return self::$_instance; 
        } 
 
        /**
         * Log message exception
         * 
         * @param string $message
         * @param string $file
         * @param string $method
         * @param int $line
         * @return boolean
         * @throws Exception When file cann't be written
         */
	public function log($message = '', $file = '', $method = '', $line = '') {
                
                $log = '';
                $log .= 'Start time: '.date('d.m.Y h:i:s').PHP_EOL;
		$log .= 'Exception: '. $message. ' for ' . $method .' on Line Number '. $line .' in File ' . $file .PHP_EOL;
                $log .= 'End time: '.date('d.m.Y h:i:s').PHP_EOL;
                $log .= '-------------------------------'.PHP_EOL;
		if($logged = !file_put_contents($this->file, $log, FILE_APPEND)) {
                        throw new Exception("Can\'t write to log");
		}
                
                return $logged;
                
	}
        
        /**
         * Get log file path
         * 
         * @return string
         */
        public function getFile()
        {
            return $this->file;
        }
}