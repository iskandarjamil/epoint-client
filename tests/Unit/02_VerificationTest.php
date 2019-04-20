<?php

namespace Epoint\Test\Unit;

use EpointClient\Execption\TypeException;
use EpointClient\Verification;
use PHPUnit\Framework\TestCase;

class VerficationTest extends TestCase
{
    public function testClassExists()
    {
        $this->assertNotNull(Verification::class);
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
        $this->assertEquals(101, $test['code']);
        $this->assertStringContainsString('invalid card', $test['message']);
    }

    public function testValidCard()
    {
        $epoint = new Verification('9999000220220783', '0122222222');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertEquals(200, $test['code']);
    }
}
