<?php 

namespace Datix\Test;
use Datix\Log;

class LogTest extends \PHPUnit_Framework_TestCase
{
        public $log;
        
        public function setUp() {
            $this->log = Log::instance('test.log');
        }
        
        public function testGetInstanceOfLog ()
        {
                $this->assertInstanceOf('\Datix\Log', $this->log);
        }
        
        public function testFileLoggedMessages ()
        {
                $message = "Test File Logs Messages";
                $result = $this->log->log($message);
                $this->assertFalse($result);
        }
        
        public function tearDown() {
            
            register_shutdown_function(function() {
                if(file_exists($this->log->getFile())) {
                    unlink($this->log->getFile());
                }
            });
            
        }
}