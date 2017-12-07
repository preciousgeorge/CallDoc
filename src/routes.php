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
        
        $this->delete('/{id}', \CallDoc\Dispatchers\UserDispatcher::class . ':delete');

        $this->post('/login', \CallDoc\Dispatchers\UserDispatcher::class .':login');
        
        $this->post('/signup', \CallDoc\Dispatchers\UserDispatcher::class . ':createUser');
        
        

    });




});
