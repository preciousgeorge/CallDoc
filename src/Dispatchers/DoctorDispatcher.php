<?php

namespace CallDoc\Dispatchers;


use \Psr\Http\Message\ServerRequestInterface as Request; 
use \Psr\Http\Message\ResponseInterface as Response;
use CallDoc\Models\Users;
use CallDoc\Lib\Authenticate;
use CallDoc\Models\Consults;
use CallDoc\Models\BlackList;

class DoctorDispatcher 
{



    protected $container;
    
    // constructor receives container instance
    public function __construct(\Pimple\Container $container) {
           $this->container = $container;
    }


     /**
     * fetch patients records
     * 
     * 
     */
    public function getPatients(Request $request, Response $response, Array $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        
        if ($id = (new Authenticate())->checkToken($token)) {
            echo $id;
            $consult = Consults::where(['consulting' => $id])->get(['user_id']);
            $userKeys = array_keys(\array_column($consult->toArray(), null, 'user_id')); 
        
             if ($userKeys) {
                $user_ids = implode($userKeys, ",");
                
                 $patients = Users::whereRaw("id in (".$user_ids.")");
                
                 return $response->WithJson(['success' => 'patients records fetch successful', 'data' => $patients->get()->toArray()]);
             }

             return $response->WithJson(['warning'=> 'No user has added you for consultation yet', 'code' => 200], 200);
             
        }
 
         return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }


     /**
     * Stop a user from accessing doctors details
     */
    public function blockUser(Request $request, Response $response)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        $data = $request->getParsedBody();

        if ($id = (new Authenticate())->checkToken($token)) {
            $unauthorized = \CallDoc\Lib\Authorize::canActOnUser($token);
            if ($unauthorized) {
                return $response->WithJson(['error'=> 'You are not allowed to perform this action', 'code' => 401], 401);
            }
             $blackList = new BlackList();
             $blackList->user_id = $data['patient'];
             $blackList->doctors_id = $id;
             $blackList->black_listed = $data['black_listed'];

             $result = $blackList->save();
            if ($result) {
                return $response->WithJson(['success'=> 'Patient Blacklisted successfully']);
            }
            return $response->WithJson(['error'=> 'An error occured', 'code' => 400], 400);
        }

        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }

    /**
     * Remove a patient fron consulting a doctor
     */
    public function deleteUser(Request $request, Response $response, $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        $data = $request->getParsedBody();
        if ($id = (new Authenticate())->checkToken($token)) {
            $unauthorized = \CallDoc\Lib\Authorize::canActOnUser($token);
            if ($unauthorized) {
                return $response->WithJson(['error'=> 'You are not allowed to perform this action', 'code' => 401], 401);
            }
            $consult = Consults::where(["user_id" => $args['patient'], "consulting" => $id]);
            if ($consult->delete()) {
                return $response->WithJson(['success'=> 'Record removed successfully']);
            }
            return $response->WithJson(['error'=> 'Record could not be removed', 'code' => 400], 400);
        }
        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);

    }
}