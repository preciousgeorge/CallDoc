<?php

namespace CallDoc\Lib;

use CallDoc\Models\Users;
use CallDoc\Models\Tokens;

use \Firebase\JWT\JWT;
use Carbon\Carbon;

/**
 * Authentication Object
 * To check that a user has a record or the users token has not expired
 * Create a Jwt token for users to authenticate with
 * 
 * @author Precious George <precious.george.o@gmail.com>
 */
class Authenticate 
{



    private $jwt_key = 'LCDALCO1820';




    /**
     * Get Token 
     * 
     * @param string $email
     * @param string $password
     * @return array $token || int 0
     */
    public function getToken($email, $password)
    {
        
        $user = Users::where('email', $email)->first();
        if (password_verify($password, $user['password'])) {
            return $this->createToken($user['id']);   
        }
        return 0;
    }


    /**
     * Create token using Firebase JWT
     * Insert jwt token into tokens table and return the token in an array
     * 
     * @param int $id
     * @return array 
     */
    public function createToken($id)
    {
        $expiry_time = time() + (3600 * 24 * 15);
        $token_array = [
                        "iss" => "localhost",
                        "iat"     => time(),
                        "exp"     =>  $expiry_time,
                        "nbf" => 1357000000
                       ];
        $jwt = JWT::encode($token_array, $this->jwt_key); 
        $token = new Tokens();
        $token->value = $jwt;
        $token->user_id = $id;
        $token->expiry = date('Y-m-d H:i:s', $expiry_time);

        $saved = $token->save();
        
        return Tokens::find($token->id);
    }


    /**
     * Check that token is still valid
     * If token is still valid return the user's id for use to authorize requests
     * If token is invalid or expired return  false
     * 
     * @param string $token
     * 
     * @return int $user_id
     */
    public function checkToken($token) 
    {
        $cond = ['value' => $token];
        $token = Tokens::where($cond)->whereDate('expiry', '>=', Carbon::now()->toDateString())->first();  
        if ($token) {
            return $token['user_id'];
        }
        return 0;
    }

}