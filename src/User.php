<?php 

namespace Datix;
use Datix\Log;

/**
 * User Class
 */

class User 
{
        /*
         * Minimum password length
         * 
         * @var int
         */
        const MIN_PASS_LENGTH = 6;
        
        /**
         *
         * @var DB  
         */
        private $db = null;
        
        /**
         * Value object of database
         * 
         * @param \Datix\DB $db
         * @throws Exception When null or empty value passed into the constructor
         */
        public function __construct(DB $db) {
            
                try {
                    if (is_null($db) || empty($db)) {
                        throw new \Exception("Can\'t instatiate DB class as null is passed");
                    }
                
                    $this->db = $db;
                    
                } catch (\Exception $e) {
                    Log::instance()->log($e->getMessage(), __FILE__, __METHOD__, __LINE__ ); 
                }
        }

        /**
         * Create new user
         * 
         * @param string $username
         * @param string $password
         * @throws \Exception When password size is less then 6
         */
        public function newUser($username, $password) 
        {
                try{
                    $passSize = $this->checkPasswordSize($password);

                    if ($passSize) {

                        $cryptPassword = $this->passwordCrypt($password);
                        $this->db->insert ($username, $cryptPassword);
                    }
                } catch (\Exception $e){
                    Log::instance()->log($e->getMessage(), __FILE__, __METHOD__, __LINE__ ); 
                }
        }

        /**
         * Change user password
         * 
         * @param string $username
         * @param string $password
         * @throws \Exception When user password is less then 6 or doesn't exisit
         */
        public function changePassword($username, $password) 
        {
                try {
                    $passSize = $this->checkPasswordSize($password);
                    $userExists = $this->UserExists($username);

                    if ($passSize && $userExists) {
                        $cryptPassword = $this->passwordCrypt($password);
                        $this->db->update ($username, $cryptPassword);
                    }
                } catch (\Exception $e){
                    Log::instance()->log($e->getMessage(), __FILE__, __METHOD__, __LINE__ ); 
                }
        }

        /**
         * Delete user
         * 
         * @param string $username
         * @throws \Exception When user doesn't exist
         */
        public function deleteUser($username) 
        {
                try {
                    $userExists = $this->UserExists($username);

                    if ($userExists) {

                        $this->db->delete ($username);
                    }
                } catch (\Exception $e){
                    Log::instance()->log($e->getMessage(), __FILE__, __METHOD__, __LINE__ ); 
                }
        }

        /**
         * Check if your exist
         * 
         * @param string $username
         * @return boolean
         * @throws \Exception When user doesn't exist
         */
        public function UserExists($username) 
        {
                try {
                    $user = $this->db->get($username);

                    if (empty($user)) {
                        throw new \Exception("User doesn't Exist");
                    }

                    return true;
                } catch (\Exception $e) {
                    Log::instance()->log($e->getMessage(), __FILE__, __METHOD__, __LINE__ ); 
                    return false;
                }
        }

        /**
         * Check user password size
         * 
         * @param string $password
         * @return boolean
         * @throws \Exception When password is less then 6 character
         */
        private function checkPasswordSize($password) 
        {
                if (strlen($password) < self::MIN_PASS_LENGTH) {
                    throw new \Exception("Password is less then 6 character");
                }

                return true;
        }

        /**
         * Encrypt password
         * 
         * @param string $password
         * @return string
         */
        public function passwordCrypt($password) 
        {
                return md5($password);
        }
}
