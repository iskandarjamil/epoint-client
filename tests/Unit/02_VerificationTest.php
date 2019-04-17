<?php

namespace Epoint\Test\Unit;

use EpointClient\Verification;
use PHPUnit\Framework\TestCase;

class VerficationTest extends TestCase
{
    public function testClassExists()
    {
        $this->assertNotNull(Verification::class);
    }

    /**
     * @expectedException EpointClient\Execption\TypeException
     */
    public function testExpectedExeptionOnEmpty()
    {
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
        $this->assertContains('invalid card', $test['message']);
    }
}
