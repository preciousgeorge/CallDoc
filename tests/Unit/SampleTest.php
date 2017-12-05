<?php
use PHPUnit\Framework\TestCase;

class Simple
{
    public function a($a1)
    {
    }
}

class SimpleTest extends TestCase
{
    public function testSimple()
    {
        $mock = $this->createMock(Simple::class);

        $mock->expects($this->once())
             ->method('a')
             ->with($this->callback(
                 function ($a) { return $a === 'b'; }
             ));

        $mock->a('b');
    }
}