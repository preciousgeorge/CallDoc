<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->group('/api/v1', function () {

    $this->group('/user', function () {
       
        $this->get('/all', \CallDoc\Dispatchers\UserDispatcher::class . ':getAllUsers');

        $this->get('/profile/{id}', \CallDoc\Dispatchers\UserDispatcher::class . ':getUserWithId');

        $this->post('/login', \CallDoc\Dispatchers\UserDispatcher::class .':login');
        
        $this->post('/signup', \CallDoc\Dispatchers\UserDispatcher::class . ':createUser');
        
        

    });

    $this->group('/patient', function () {
          $this->get('/all/doctors', \CallDoc\Dispatchers\PatientDispatcher::class . ':getDoctors');

          $this->get('/own/doctors', \CallDoc\Dispatchers\PatientDispatcher::class . ':getOwnDoctors');

          $this->post('/consult', \CallDoc\Dispatchers\PatientDispatcher::class . ':consultDoctor');

          $this->delete('/unconsult/{id}', \CallDoc\Dispatchers\PatientDispatcher::class . ':unConsultDoctor');


    });

    $this->group('/doctor', function () {
        
        $this->get('/list', \CallDoc\Dispatchers\DoctorDispatcher::class . ':getPatients');

        // send a post request using
        $this->post('/block', \CallDoc\Dispatchers\DoctorDispatcher::class . ':blockUser');

        $this->delete('/remove-patient/{patient}', \CallDoc\Dispatchers\DoctorDispatcher::class . ':deleteUser');
    });


    $this->group('/question', function () {
        
        $this->get('/all[/{id}]', \CallDoc\Dispatchers\QuestionDispatcher::class . ':getAll');

        $this->post('/ask', \CallDoc\Dispatchers\QuestionDispatcher::class . ':askQuestion');
    });

    $this->group('/answer', function () {
           
         $this->get('/{question_id}', CallDoc\Dispatchers\AnswerDispatcher::class . ':getAnswer');

         $this->post('/add', CallDoc\Dispatchers\AnswerDispatcher::class . ':answerQuestion');
    });




});
