<?php

namespace Epoint\Test\Unit;

use EpointClient\Execption\TypeException;
use EpointClient\Verification;
use PHPUnit\Framework\TestCase;

class VerficationTest extends TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists(Verification::class));
    }

    public function testExpectedExeptionOnEmpty()
    {
        $this->expectException(TypeException::class);

        $epoint = new Verification();
        $epoint->execute();
    }

    public function testVerfication()
    {
        $epoint = new Verification('1', '1');
        $test = $epoint->execute();

        $this->assertTrue($test);
    }

    public function testOuputNotEmpty()
    {
        $epoint = new Verification('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();

        $this->assertNotEmpty($test);
    }

    public function testExpectedOutputInvalidCard()
    {
        $epoint = new Verification('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertEquals(101, $test->code);
        $this->assertStringContainsString('invalid card', $test->message);
    }

    public function testExpectedOutputUnableToVerify()
    {
        $epoint = new Verification('1', '1');
        $epoint->execute();

        $test = $epoint->isValid();
        $this->assertFalse($test);
    }

    public function testCheckStatusIsNotValid()
    {
        $epoint = new Verification('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertNotEquals(200, $test->code);
    }

    // public function testSuccessVerify()
    // {
    //     $epoint = new Verification('9999000220220783', '0122222222');
    //     $epoint->execute();

    //     $test = $epoint->getOutput();
    //     $this->assertEquals(200, $test->code);
    // }

    // public function testCheckStatusIsValid()
    // {
    //     $epoint = new Verification('1', '1');
    //     $epoint->execute();

    //     $test = $epoint->isValid();
    //     $this->assertTrue($test);
    // }
}
