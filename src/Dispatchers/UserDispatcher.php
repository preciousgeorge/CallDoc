<?php

namespace CallDoc\Dispatchers;

use \Psr\Http\Message\ServerRequestInterface as Request; 
use \Psr\Http\Message\ResponseInterface as Response;
use CallDoc\Models\Users;
use CallDoc\Lib\UserValidation;
use CallDoc\Lib\Authenticate;
use CallDoc\Models\Consults;
use CallDoc\Models\BlackList;

/**
 * User Dispatcher
 * dispatches actions to user Model
 * 
 * @category Dispatcher
 * @author Precious George <precious.george.o@gmail.com>
 * 
 */
class UserDispatcher extends UserValidation
{

    protected $container;
    
    // constructor receives container instance
    public function __construct(\Pimple\Container $container) {
           $this->container = $container;
    }


    /**
     * Create a user
     * 
     * @return json
     */
    public function createUser(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        if ($this->validateUser($body)) {

            $user = new Users();
            
            $user->email = $body['email'];
            $user->password = password_hash($body['password'], PASSWORD_DEFAULT);
            $user->name = $body['name'];
            $user->role_id = $body['role'];
            
            $saved = $user->save();
            
            if ($saved) {
                $new_user = Users::find($user->id);
                return $response->WithJson(['success' => 'User created successfully', 'data' => $new_user]);
            }
            
        }
        

        return $response->WithJson(['error' => 'User could not be created', 'code' => 400], 400);
    }

    
    /**
     * Authenticate and get user values
     */
    public function login(Request $request, Response $response)
    {
         $body = $request->getParsedBody();
         $auth = new Authenticate();
         $token = $auth->getToken($body['email'], $body['password']);
         if ($token) {
            return $response->WithJson(['token' => $token['value']]);
         }
         return $response->WithJson(['error' => 'Problem creating token, please contact admin', 'code' => 400], 400);
    }

    /**
     * Fetch all users
     */
    public function getUsers(Request $request, Response $response)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
         
        if ((new Authenticate())->checkToken($token)) {
              $users = Users::all();
            if ($users) {
                return $response->WithJson($users);
            }
            return $response->WithJson(['error'=> 'No records found', 'code' => 400], 400);
              
        }
        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
  
    }



    /**
     * Fetch a particular user value
     */
    public function getUserWithId(Request $request, Response $response, Array $args)
    {
        $id = $args['id'];
        $token = $request->getHeader('HTTP_JWT')[0];
         
        if ((new Authenticate())->checkToken($token)) {
            $user = Users::find($id);
            if ($user) {
                return $response->WithJson($user);
            }
              return $response->WithJson(['error'=> 'No record found', 'code' => 400], 400);
              
        }
        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }


   

   

}