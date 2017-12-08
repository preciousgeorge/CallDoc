<?php

namespace CallDoc\Dispatchers;

use \Psr\Http\Message\ServerRequestInterface as Request; 
use \Psr\Http\Message\ResponseInterface as Response;
use CallDoc\Models\Users;
use CallDoc\Lib\Authenticate;
use CallDoc\Models\Questions;
use CallDoc\Models\Answers;

/**
 * Question Dispatcher
 * dispatches actions to Questions Model
 * 
 * @category Dispatcher
 * @author Precious George <precious.george.o@gmail.com>
 * 
 */
class QuestionDispatcher 
{

    /**
     * Get all questions or use question id to get one
     */
    public function getAll(Request $request, Response $response, $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        
        if ((new Authenticate())->checkToken($token)) {
            if ($args['doctor']) {
                $questions = Questions::where(['doctors_id' => $args['doctor']])->get();
            } else {
                $questions = Questions::all()->toArray();
            }
            
            if ($questions) {
                return $response->WithJson(['success' => 'questions', 'data' => $questions]);
            }
            return $response->WithJson(['warning' => 'No question available']);
        }
        return $response->WithJson(['error' => 'Token Invalid', 'code' => 400], 400);
    }   
    
    
    
    

    /**
     * Ask Question by sending 
     */
    public function askQuestion(Request $request, Response $response)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        $data = $request->getParsedBody();

        if ($id = (new Authenticate())->checkToken($token)) {
        
            $question = new Questions();
            $question->user_id = $id;
            $question->question = $data['question'];

            $result = $question->save();
            if ($result) {
                return $response->WithJson(['success' => 'Created Successfully', 'data' => ['id' => $question->id]]);
            }
            return $response->WithJson(['warning' => 'Question could not be created']);
        }
        return $response->WithJson(['error' => 'Token invalid', 'code' => 400], 400);
    }   
        
}