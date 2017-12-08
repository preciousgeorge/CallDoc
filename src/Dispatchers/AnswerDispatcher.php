<?php

namespace CallDoc\Dispatchers;

use \Psr\Http\Message\ServerRequestInterface as Request; 
use \Psr\Http\Message\ResponseInterface as Response;
use CallDoc\Models\Users;
use CallDoc\Models\Answers;
use CallDoc\Lib\Authenticate;
use CallDoc\Lib\Authorize;

/**
 * Answer Dispatcher
 * dispatches actions to Answers Model
 * 
 * @category Dispatcher
 * @author Precious George <precious.george.o@gmail.com>
 * 
 */
class AnswerDispatcher 
{

    /**
     *  get Answer to question
     */
    public function getAnswer(Request $request, Response $response, $args)
    {
        $token = $request->getHeader('HTTP_JWT')[0];
        
        if ((new Authenticate())->checkToken($token)) {
            $answer = Answers::where(['question_id' => $args['question_id']])->get();
            if (!$answer->isEmpty()) {
                return $response->WithJson(['success' => 'Request successful', 'data' => $answer->toArray()]);
            }
            return $response->WithJson(['success' => 'No answer for this question yet']);
        }
        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
    }
    
    /**
     * Answer questions
     */
    public function answerQuestion(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();

        $token = $request->getHeader('HTTP_JWT')[0];

        if ($id = (new Authenticate())->checkToken($token)) {

            if (Authorize::canAddAnswer($token)) {
                $answer = new Answers();
                $answer->question_id = $data['question'];
                $answer->doctors_id = $id;
                $answer->answer = $data['answer'];
                if ($answer->save()) {
                    return $response->WithJson(['success' => 'Question answered successfully']);
                }
                return $response->WithJson(['warning' => 'Question not answered!!!']);
            }
            return $response->WithJson(['error' => 'UnAuthorized action'], 401);
           
        }

        return $response->WithJson(['error'=> 'Invalid token', 'code' => 400], 400);
        
    }   

}