<?php

namespace Epoint\Test\Unit;

use EpointClient\Transaction;
use EpointClient\Exception\TypeException;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    protected $classname;

    /**
     * @before
     */
    public function setUpData()
    {
        $this->classname = Transaction::class;
    }

    public function testClassExists()
    {
        $this->assertTrue(class_exists($this->classname));
    }

    public function testExpectedExeptionRequireCard()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('Please provide card no.');

        $epoint = new $this->classname();
        $epoint->execute();
    }

    public function testExpectedExeptionRequireVerification()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('Please provide verification code.');

        $epoint = new $this->classname('1');
        $epoint->execute();
    }

    public function testExpectedInvalidCardNo()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertFalse($epoint->isValid());
        $this->assertEquals('You have entered an invalid card no.', $test->message);
    }

    public function testExpectedInvalidCardVerfication()
    {
        $epoint = new $this->classname('9999000220220783', '1');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertFalse($epoint->isValid());
        $this->assertEquals('Your verification code is invalid.', $test->message);
    }

    public function testTransaction()
    {
        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->execute();

        $test = $epoint->getTransaction();
        $this->assertNotNull($test);
        $this->assertTrue($epoint->isValid());
    }
}
