<?php

namespace Epoint\Test\Unit;

use EpointClient\Execption\TypeException;
use EpointClient\RegisterCard;
use PHPUnit\Framework\TestCase;

class RegisterCardTest extends TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists(RegisterCard::class));
    }

    public function testExpectedExeptionOnEmpty()
    {
        $this->expectException(TypeException::class);

        $epoint = new RegisterCard();
        $epoint->execute();
    }
}
