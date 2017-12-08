<?php

namespace CallDoc\Dispatchers;


use \Psr\Http\Message\ServerRequestInterface as Request; 
use \Psr\Http\Message\ResponseInterface as Response;
use CallDoc\Models\Users;
use CallDoc\Lib\Authenticate;
use CallDoc\Models\Consults;
use CallDoc\Lib\Authorize;



class PatientDispatcher
{

    protected $container;
    
    // constructor receives container instance
    public function __construct(\Pimple\Container $container) {
           $this->container = $container;
    }



     /**
     * consult doctor 
     * 
     */
    public function consultDoctor(Request $request, Response $response, Array $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        $data = $request->getParsedBody();
        
        if ($id = ( new Authenticate())->checkToken($token)) {
            $unauthorized = \CallDoc\Lib\Authorize::canViewDoctor($id, $data['doctor']);
            if ($unauthorized) {
                return $response->WithJson(['error'=> 'Doctor has blacklisted this user', 'code' => 400], 400);
            }
            $cons = Consults::where(['consulting' => $data['doctor'], 'user_id' => $id])->get();

            if (!$cons->isEmpty()) {
                return $response->WithJson(['warning'=> 'Record already exists']);
            }
            $consult = new Consults();

            $consult->user_id = $id;
            $consult->consulting = $data['doctor'];

            $result = $consult->save();
            if ($result) {
                return $response->WithJson(['success'=> 'Record added successfully']);
            } else {
                return $response->WithJson(['error'=> 'Record could not be added', 'code' => 400], 400);
            }
        }
        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }


    /**
     * Remove doctor from list of doctors to consult
     * 
     */
    public function unConsultDoctor(Request $request, Response $response, Array $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        $data = $request->getParsedBody();
        
        if ($id = (new Authenticate())->checkToken($token)) {
            $consult = Consults::where(['consulting' => $data['doctor'], 'user_id' => $id])->delete();
            if ($consult) {
                return $response->WithJson(['success'=> 'Record removed successfully']);
            }
            return $response->WithJson(['error'=> 'Record could not be removed', 'code' => 400], 400);
        }

        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }


    /**
     *  get doctors that have ben added to user's consulting list
     * 
     */
    public function getOwnDoctors(Request $request, Response $response, Array $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        
        if ($id = (new Authenticate())->checkToken($token)) {
            
            $consult = Consults::where(['user_id' => $id])->get(['consulting']);

            $consultKeys= array_keys(\array_column($consult->toArray(), null, 'consulting')); 
            
            $consulting = implode($consultKeys, ",");
           
            $doctors = Users::whereRaw("id in (".$consulting.")");
           
            return $response->WithJson($doctors->get()->toArray());

        }

        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }

    /**
     * fetch all doctors 
     * 
     * 
     */
    public function getDoctors(Request $request, Response $response, Array $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        
        if ((new Authenticate())->checkToken($token)) {
            
           
            $role_id = Authorize::getRoleId(Authorize::DOCTOR);
            $doctors = Users::where(['role_id' => $role_id]);
           
            return $response->WithJson($doctors->get()->toArray());

        }

        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }


}