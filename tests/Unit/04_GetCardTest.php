<?php

namespace Epoint\Test\Unit;

use EpointClient\Exception\TypeException;
use EpointClient\GetCard;
use PHPUnit\Framework\TestCase;

class GetCardTest extends TestCase
{
    protected $classname;

    /**
     * @before
     */
    public function setUpData()
    {
        $this->classname = GetCard::class;
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

    public function testGetUser()
    {
        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertEquals(200, $test->code);
        $this->assertNotNull($test->data);
    }

    public function testValidGetUser()
    {
        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->execute();

        $test = $epoint->getOutput();
        $data = $test->data;

        $this->assertTrue(isset($data->member_id));
        $this->assertTrue(isset($data->first_name));
        $this->assertTrue(isset($data->last_name));
        $this->assertTrue(isset($data->email));
        $this->assertTrue(isset($data->mobile));
        $this->assertTrue(isset($data->date_registered));
        $this->assertTrue(isset($data->wallets));
        $this->assertEquals('9999000220220783', current($data->wallets)->card_id);
    }
}
