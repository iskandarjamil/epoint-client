<?php

namespace Epoint\Test\Unit;

use EpointClient\Exception\TypeException;
use EpointClient\Verification;
use PHPUnit\Framework\TestCase;

class VerificationTest extends TestCase
{
    protected $classname;

    /**
     * @before
     */
    public function setUpData()
    {
        $this->classname = Verification::class;
    }

    public function testClassExists()
    {
        $this->assertTrue(class_exists($this->classname));
    }

    public function testExpectedExeptionOnEmpty()
    {
        $this->expectException(TypeException::class);

        $epoint = new $this->classname();
        $epoint->execute();
    }

    public function testVerification()
    {
        $epoint = new $this->classname('1', '1');
        $test = $epoint->execute();

        $this->assertTrue($test);
    }

    public function testOuputNotEmpty()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();

        $this->assertNotEmpty($test);
    }

    /**
     * @dataProvider getCardProvider
     */
    public function testCardNoTrimValue($input, $expected)
    {
        $epoint = new $this->classname($input, '1');
        $epoint->execute();

        $this->assertEquals($expected, $epoint->getCardNo());
    }

    /**
     * @dataProvider getVerificationCodeProvider
     */
    public function testVerificationCodeTrimValue($input, $expected)
    {
        $epoint = new $this->classname('1', $input);
        $epoint->execute();

        $this->assertEquals($expected, $epoint->getVerificationCode());
    }

    public function testExpectedOutputInvalidCard()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertEquals(101, $test->code);
        $this->assertStringContainsString('invalid card', $test->message);
    }

    public function testExpectedOutputUnableToVerify()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->execute();

        $test = $epoint->isValid();
        $this->assertFalse($test);
    }

    public function testCheckStatusIsNotValid()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertNotEquals(200, $test->code);
    }

    public function testExpectedInvalidCardNo()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertFalse($epoint->isValid());
        $this->assertEquals('You have entered an invalid card no.', $test->message);
    }

    public function testExpectedInvalidCardVerification()
    {
        $epoint = new $this->classname('9999000220220783', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertFalse($epoint->isValid());
        $this->assertEquals('Your verification code is invalid.', $test->message);
    }

    public function testSuccessVerify()
    {
        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertEquals(200, $test->code);
    }

    public function testCheckStatusIsValid()
    {
        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->execute();

        $test = $epoint->isValid();
        $this->assertTrue($test);
    }

    public function getCardProvider()
    {
        return [
            [' 1 ', '1'], //
            [' 1', '1'],
            ['1 ', '1'],
            ['12e12d1d12d ', '12e12d1d12d'],
            [' 12e12d1d12d ', '12e12d1d12d'],
            [' 12e12d1d12d      ', '12e12d1d12d'],
            ['12e12d1d12d      ', '12e12d1d12d'],
            ['     12e12d1d12d', '12e12d1d12d'],
        ];
    }

    public function getVerificationCodeProvider()
    {
        return [
            [' 1 ', '1'], //
            [' 1', '1'],
            ['1 ', '1'],
            ['12e12d1d12d ', '12e12d1d12d'],
            [' 12e12d1d12d ', '12e12d1d12d'],
            [' 12e12d1d12d      ', '12e12d1d12d'],
            ['12e12d1d12d      ', '12e12d1d12d'],
            ['     12e12d1d12d', '12e12d1d12d'],
        ];
    }
}
