<?php

namespace Datix\Test;

use Datix\User;
use Datix\DB;

class UserTest extends \PHPUnit_Framework_TestCase 
{

        protected $db;
        protected $user;
        protected $mockUser;

        public function setUp ()
	{
		$this->db = $this->getMockBuilder('Datix\DB')
				         ->setMethods(['insert','delete','update','get'])
				         ->getMock();
                
                $this->mockUser = $this->getMockBuilder('Datix\User')
                                         ->setConstructorArgs([$this->db])
                                         ->setMethods(['passwordCrypt'])
                                         ->getMock();
                
                $this->user = new User($this->db);
                              
	}
        
        public function testCreateUser ()
	{
		$username = 'john';
		$password = 'pass123';
            
		$this->db->expects($this->once())->method('insert')->with($username, md5($password));

		$this->user->newUser($username, $password);
	}
        
        public function testCreateUserWithShortPassword ()
        {
                $username = 'john';
                $password = 'abc12';
                
                $this->db->expects($this->never())->method('insert');
                
                $this->user->newUser($username, $password);
        }
        
        public function testChangePassword ()
        {
                $username = 'john';
                $newPassword = 'N3wpass!9';
                
                $this->db->expects($this->once())->method('get')->with($username)->willReturn("something");
                $this->db->expects($this->once())->method('update')->with($username, md5($newPassword));
                
                $this->user->changePassword($username, $newPassword);
        }
        
        public function testChangePasswordWithShortPassword ()
        {
                $username = 'john';
                $newPassword = 'N3wpa';
                
                $this->db->expects($this->never())->method('update');
                
                $this->user->changePassword($username, $newPassword);
        }
        
        public function testChangePassworOfNonExistingUser ()
        {
                $username = 'john';
                $newPassword = 'N3wpass!9';
                
                $this->db->expects($this->once())->method('get')->with($username)->willReturn(null);
                
                $this->db->expects($this->never())->method('update');
                
                $this->user->changePassword($username, $newPassword);
        }
        
        public function testDeleteUser ()
        {
                $username = 'john';
                
                $this->db->expects($this->once())->method('get')->with($username)->willReturn("something");
                
                $this->db->expects($this->once())->method('delete')->with($username);
                
                $this->user->deleteUser($username);
        }
        
        public function testDeleteNonExistingUser ()
        {
                $username = 'james';
                
                $this->db->expects($this->once())->method('get')->with($username)->willReturn(null);
                
                $this->db->expects($this->never())->method('delete');
                
                $this->user->deleteUser($username);
        }
        
        public function testUserDoesntExists ()
        {
            $username = 'johnny';
            
            $this->db->expects($this->once())->method('get')->with($username)->willReturn(null);
            $checkUser = $this->user->UserExists($username);
            
            $this->assertFalse($checkUser);
        }
        
        /**
         * 
         * @param string $encryptedPassword Encrypted Password
         * @param string $password Password
         * 
         * @dataProvider providerTestDifferentEcryptionTypeForCreateUser
         */
        public function testDifferentEncryptionTypeForCreateUser ($encryptedPassword, $password)
        {
                $username = 'john';
                
                $this->mockUser->expects($this->once())->method('passwordCrypt')->will($this->returnValue($encryptedPassword));
               
                $this->db->expects($this->once())->method('insert')->with($username, $encryptedPassword);
                               
                $this->mockUser->newUser($username, $password);
                
        }
        
        public function providerTestDifferentEcryptionTypeForCreateUser ()
        {
                $password = 'pass123';
                
                return [
                    [md5($password), $password],
                    [sha1($password), $password],
                    [crypt($password), $password],
                    [password_hash($password, PASSWORD_DEFAULT), $password],
                    
                ];
        }
}	
