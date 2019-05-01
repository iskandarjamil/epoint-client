<?php

namespace Epoint\Test\Unit;

use EpointClient\Exception\TypeException;
use EpointClient\GetCard;
use EpointClient\TopupCard;
use PHPUnit\Framework\TestCase;

class TopupTest extends TestCase
{
    protected $classname;
    protected $transactionData;

    /**
     * @before
     */
    public function setUpData()
    {
        $rand = rand(100,300);
        $this->classname = TopupCard::class;
        $this->transactionData = [
            'amount' => 10,
            'order_no' => 'T' . time() . $rand,
            'receipt_no' => 'R' . time() . $rand,
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

    public function testExpectedExceptionRequiredAmount()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('Amount is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->addTransaction([]);
        $epoint->execute();
    }

    public function testExpectedExceptionRequiredOrderNo()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('Order no is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->addTransaction([
            'amount' => 10,
        ]);
        $epoint->execute();
    }

    public function testExpectedExceptionRequiredReceiptNo()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('Receipt no is required.');

        $epoint = new $this->classname('1', '1');
        $epoint->addTransaction([
            'amount' => 10,
            'order_no' => 'T0001'
        ]);
        $epoint->execute();
    }

    /**
     * @dataProvider getExceptionProvider
     */
    public function testExpectedExceptionIfMissingArgument($input)
    {
        $this->expectException(TypeException::class);

        $epoint = new $this->classname('1', '1');
        $epoint->addTransaction($input);
        $epoint->execute();
    }

    /**
     * @dataProvider getCheckerTransactionProvider
     */
    public function testExpectedValidCustomerData($data, $expected)
    {
        $epoint = new $this->classname('1', '1');
        $epoint->addTransaction($data);
        $epoint->execute();
        $topup = $epoint->getTopup();

        $this->assertEquals($expected['amount'], $topup->amount);
        $this->assertEquals($expected['order_no'], $topup->order_no);
        $this->assertEquals($expected['receipt_no'], $topup->receipt_no);
    }

    public function testExpectedInvalidCardNo()
    {
        $epoint = new $this->classname('1', '1');
        $epoint->addTransaction($this->transactionData);
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertFalse($epoint->isAccepted());
        $this->assertEquals('You have entered an invalid card no.', $test->message);
    }

    public function testExpectedInvalidCardVerfication()
    {
        $epoint = new $this->classname('9999000220220783', '1');
        $epoint->addTransaction($this->transactionData);
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertFalse($epoint->isAccepted());
        $this->assertEquals('Your verification code is invalid.', $test->message);
    }

    public function testTopupCard()
    {
        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->addTransaction($this->transactionData);
        $epoint->execute();

        $test = $epoint->getOutput();
        $this->assertEquals(200, $test->code);
        $this->assertNotNull($test->data);

        $this->assertTrue($epoint->isAccepted());
    }

    public function testTopupAmountBeforeAfter()
    {
        $epoint = new GetCard('9999000220220783', '0122222222');
        $epoint->execute();
        $card = $epoint->getWallet();
        $before = $card->stored_value;

        $epoint = new $this->classname('9999000220220783', '0122222222');
        $epoint->addTransaction($this->transactionData);
        $epoint->execute();

        $epoint = new GetCard('9999000220220783', '0122222222');
        $epoint->execute();
        $card = $epoint->getWallet();
        $after = $card->stored_value;

        $amountShouldBe = (int)$before + $this->transactionData['amount'];
        $amountShouldBe = $amountShouldBe . ".00";

        $this->assertNotSame($before, $after);
        $this->assertEquals($after, $amountShouldBe);
    }

    public function getExceptionProvider()
    {
        return [
            [
                [
                    'amount' => 10,
                ]
            ],
            [
                [
                    'order_no' => 'T0001',
                ]
            ],
            [
                [
                    'receipt_no' => 'R0001',
                ]
            ],
            [
                [
                    'amount' => 10,
                    'order_no' => 'T0001',
                ]
            ],
            [
                [
                    'amount' => 10,
                    'receipt_no' => 'R0001',
                ]
            ],
            [
                [
                    'order_no' => 'T0001',
                    'receipt_no' => 'R0001',
                ]
            ],
        ];
    }

    public function getCheckerTransactionProvider()
    {
        $output = [
            'amount' => 10,
            'order_no' => 'T0001',
            'receipt_no' => 'R00001',
        ];

        return [
            [
                [
                    'amount' => 10,
                    'order_no' => 'T0001',
                    'receipt_no' => 'R00001',
                ],
                $output,
            ],
            [
                [
                    'amount' => 10,
                    'order_no' => 'T0001 ',
                    'receipt_no' => 'R00001 ',
                ],
                $output,
            ],
            [
                [
                    'amount' => 10,
                    'order_no' => ' T0001 ',
                    'receipt_no' => ' R00001 ',
                ],
                $output,
            ],
            [
                [
                    'amount' => 10,
                    'order_no' => ' T0001',
                    'receipt_no' => ' R00001',
                ],
                $output,
            ],
        ];
    }
}
