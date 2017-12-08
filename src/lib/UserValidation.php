<?php

namespace CallDoc\Lib;

use CallDoc\Models\Users;

/**
 * User Object Validation
 * Checks values submited to user object is validate
 * @author Precious George <precious.george.o@gmail.com>
 */
class UserValidation 
{

    /**
     * Check that email address is valid or non existent in User Object
     * 
     * @param string $email
     * 
     * @return boolean
     */
    public function checkEmail($email)
    {
        if ($this->validateEmail($email)) {
            $user = Users::where('email', $email)->get();

            if ($user->isEmpty()) {
                return true;
            } 
        }
        return false;
    }

    /**
     * Check the string is a valida email address
     * 
     * @param string $email
     * 
     * @return int
     */
    public function validateEmail($email)
    {
        return (bool) (filter_var($email, FILTER_VALIDATE_EMAIL) 
             && preg_match('/@.+\./', $email));
    }

    /**
     * Check to make sure all required fields are present
     * 
     * @param array $data
     * 
     * @return boolean
     * 
     * @todo extend to return values that are absent for better error reporting
     */
    public function validateData($data) 
    {
        $truthy = [];
        
        $truthy['email'] = !empty($data['email']) ? true : false;
        $truthy['name'] = !empty($data['name']) ? true : false;
        $truthy['password'] = !empty($data['password']) ? true : false;
        $truthy['role'] = !empty($data['role']) ? true : false;
 
        return (bool)(!in_array(false, $truthy) && count($truthy));
    }


    /**
     * Validate User Data
     * 
     * @param array $data
     * 
     * @return boolean
     */
    public function validateUser($data) 
    {

        if ($this->validateData($data) && $this->checkEmail($data['email'])) {
             return true;
        }

        return false;
    }

  
}