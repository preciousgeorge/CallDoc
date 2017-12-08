<?php

namespace Tests\Functional;


class UsersTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
    public function testListUsers()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE1MTI2MDQzNzUsImV4cCI6MTUxMzkwMDM3NSwibmJmIjoxMzU3MDAwMDAwfQ.qG2NTwpxzRLfe2CjcUTJrAyvHOviPZhlmTkRxACC_BQ';
        
        $response = $this->runApp('GET', '/api/v1/user/all', [], ['jwt' => $token]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('id', (string)$response->getBody());
        $this->assertContains('name', (string)$response->getBody());
    }

    /**
     * Test that the index route with optional name argument returns a rendered greeting
     */
    public function testGetSingleUser()
    {
        $id = 1;
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE1MTI2MDQzNzUsImV4cCI6MTUxMzkwMDM3NSwibmJmIjoxMzU3MDAwMDAwfQ.qG2NTwpxzRLfe2CjcUTJrAyvHOviPZhlmTkRxACC_BQ';
        $response = $this->runApp('GET', '/api/v1/user/profile/'.$id, ['HTTP_AUTHORIZATION' => "Bearer " . $token]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('name', (string)$response->getBody());
    }

    /**
     * Test that user account can be created
     */
    public function testCreateUser()
    {
        $mockData = [
            'email' => 'presh@gmail.com',
            'password' => 'password',
            'name' => 'Precious George',
            'role' => 2
        ];

        $response = $this->runApp('POST', '/api/v1/user/signup', $mockData);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('id', (string)$response->getBody());
    }

    /**
     * Test that user account can not be created if data is incomplete
     */
    public function testUserNotCreated()
    {
        $mockData = [
            'email' => 'presh@gmail.com',
            'name' => 'Precious George',
            'role' => 2
        ];

        $response = $this->runApp('POST', '/api/v1/user/signup', $mockData);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('error', (string)$response->getBody());
    }

    /**
     *  Test that user login return token
     */
    public function testLoginUser()
    {
        $response = $this->runApp('POST', '/api/v1/user/login', ['email' => 'presh@gmail.com', 'password' => 'password']);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('token', (string)$response->getBody());
    }
}