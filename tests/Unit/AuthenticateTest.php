<?php 
namespace Tests\Unit;



use PHPUnit\Framework\TestCase;

use CallDoc\Lib\Authenticate;


/**
 * Test Authenticate object
 */
class AuthenticateTest extends bootstrap
{

    public function testTokenIsValid()
    {
        
    }


    public function testTokenExpiry()
    {

    }

    public function testAuthenticateSucess()
    {
        $email = "precious@gmail.com";
        $password = "password";
        
        $mock = $this->createMock(Authenticate::class);
    
        $mock->expects($this->once())
                     ->method('getToken')
                     ->with($this->returnValue(["token" => "AKTUBEUYTYTTYT6768742"]));
                     
        $this->assertContains('token', $mock->getToken($email, $password));
        $this->assertEquals(["token" => "AKTUBEUYTYTTYT6768742"], $mock->getToken($email, $password));

    }


    public function testCreateToken()
    {
        $id = 1;
        $auth = new Authenticate();
        $token = $auth->createToken($id);
        $this->assertContains('token', $token);
    }
}