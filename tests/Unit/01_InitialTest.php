<?php

namespace Epoint\Test\Unit;

use PHPUnit\Framework\TestCase;

class InitialTest extends TestCase
{
    public function testInitial()
    {
        $this->assertTrue(true);
    }

    public function testEnvWorking()
    {
        $this->assertNotNull(getenv('EPOINT_ENTRY_POINT'));
        $this->assertNotNull(getenv('EPOINT_DB'));
        $this->assertNotNull(getenv('EPOINT_STORE_ID'));
        $this->assertNotNull(getenv('EPOINT_USERNAME'));
        $this->assertNotNull(getenv('EPOINT_PASSWORD'));
    }
}
