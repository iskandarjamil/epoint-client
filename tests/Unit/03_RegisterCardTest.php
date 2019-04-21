<?php

namespace Epoint\Test\Unit;

use EpointClient\Exception\TypeException;
use EpointClient\RegisterCard;
use PHPUnit\Framework\TestCase;

class RegisterCardTest extends TestCase
{
    protected $classname;
    protected $customerData;

    /**
     * @before
     */
    public function setUpData()
    {
        $this->classname = RegisterCard::class;
        $this->customerData = [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'foo@bar.com',
            'phone' => '0123456789',
            'date_of_birth' => '1991-10-25',
            'gender' => 'M',
            'address_line_1' => '2 Jln Lada Hitam Taman Sri Tengah',
            'address_line_2' => 'Menara Mustapha Kamal, PJ Trade Centre',
            'address_city' => 'Kuala Lumpur',
            'address_postal_code' => 50000,
            'address_state' => 'Selangor',
            'address_country' => 'Malaysia',
        ];
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

    public function testExpecteExceptionOnEmptyUser()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('first name is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->execute();
    }

    public function testExpectedCustomerFirstNameIsRequired()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('first name is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->execute();
    }

    public function testExpectedCustomerLastNameIsRequired()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('last name is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->customer([
            'first_name' => 'Foo',
        ]);
        $epoint->execute();
    }

    public function testExpectedCustomerEmailIsRequired()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('email is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->customer([
            'first_name' => 'Foo',
            'last_name' => 'Bar',
        ]);
        $epoint->execute();
    }

    public function testExpectedCustomerPhoneIsRequired()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('phone is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->customer([
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'foo@bar.com',
        ]);
        $epoint->execute();
    }

    /**
     * @dataProvider getCheckerCustomerProvider
     */
    public function testExpectedValidCustomerData($data, $expected)
    {
        $epoint = new $this->classname('1', '1');
        $epoint->customer($data);
        $epoint->execute();
        $customer = $epoint->getCustomer();

        $this->assertEquals($expected['first_name'], $customer->first_name);
        $this->assertEquals($expected['last_name'], $customer->last_name);
        $this->assertEquals($expected['email'], $customer->email);
        $this->assertEquals($expected['phone'], $customer->phone);
    }

    public function testOuputNotEmpty()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->customer($this->customerData);
        $epoint->execute();

        $test = $epoint->getOutput();

        $this->assertNotEmpty($test);
    }

    public function testExpectedOutputInvalidCard()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->customer($this->customerData);
        $epoint->execute();

        $test = $epoint->getOutput();
        $epoint->customer($this->customerData);
        $this->assertEquals(101, $test->code);
        $this->assertStringContainsString('invalid card', $test->message);
    }

    public function testExpectedOutputUnableToVerify()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->customer($this->customerData);
        $epoint->execute();

        $test = $epoint->isRegistered();
        $this->assertFalse($test);
    }

    public function testCheckStatusIsNotValid()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->customer($this->customerData);
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertNotEquals(200, $test->code);
    }

    public function testRegisterUser()
    {
        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->customer($this->customerData);
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertEquals(200, $test->code);
        $this->assertEquals('Card has been successfully registered.', $test->message);
    }

    public function getCheckerCustomerProvider()
    {
        $output = [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'foo@bar.com',
            'phone' => '0123456789',
        ];

        return [
            [
                [
                    'first_name' => 'Foo',
                    'last_name' => 'Bar',
                    'email' => 'foo@bar.com',
                    'phone' => '0123456789',
                ],
                $output,
            ],
            [
                [
                    'first_name' => ' Foo',
                    'last_name' => ' Bar',
                    'email' => ' foo@bar.com',
                    'phone' => ' 0123456789',
                ],
                $output,
            ],
            [
                [
                    'first_name' => ' Foo    ',
                    'last_name' => ' Bar   ',
                    'email' => ' foo@bar.com   ',
                    'phone' => ' 0123456789   ',
                ],
                $output,
            ],
            [
                [
                    'first_name' => 'Foo    ',
                    'last_name' => 'Bar   ',
                    'email' => 'foo@bar.com   ',
                    'phone' => '0123456789   ',
                ],
                $output,
            ],
            [
                [
                    'first_name' => 'Foo',
                    'last_name' => 'Bar',
                    'email' => 'FOO@BAR.com',
                    'phone' => '+0123456789',
                ],
                $output,
            ],
            [
                [
                    'first_name' => 'Foo',
                    'last_name' => 'Bar',
                    'email' => 'FOO@BAR.com',
                    'phone' => '+0123456789.-',
                ],
                $output,
            ],
        ];
    }
}
