<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient;

use EpointClient\Api\ApiUpdateCardInformation;
use EpointClient\Data\Customer;
use EpointClient\Exception\TypeException;
use EpointClient\Interfaces\CardInterface;
use EpointClient\Resources\IsCardAble;
use EpointClient\Resources\IsDateTimeAble;
use EpointClient\Resources\IsResultAble;

/**
 * Epoint Card update card information process
 */
class UpdateCardInformation extends EpointClient implements CardInterface
{
    use IsDateTimeAble;
    use IsCardAble;
    use IsResultAble;

    protected $customer;
    protected $customerData = [];

    public function __construct(string $cardNo = '', string $verificationCode = '')
    {
        parent::__construct();

        $this->setCardNo($cardNo);
        $this->setVerificationCode($verificationCode);
    }

    public function customer(array $customer)
    {
        $this->customerData = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getCard()
    {
        return $this->output->data;
    }

    /**
     * Execute update card process.
     *
     * @return void
     * @throws TypeException
     */
    public function execute()
    {
        try {
            $this->validate();
        } catch (TypeException $e) {
            throw new TypeException($e->getMessage());
        }

        return true;
    }

    public function isUpdated()
    {
        if (is_null($this->getOutput())) {
            throw new TypeException("Please run `execute` method, before checking validation result.");
        }

        return $this->output->code === 200;
    }

    protected function validate()
    {
        if (empty($this->getCardNo()) || is_null($this->cardNo)) {
            throw new TypeException("Please provide card no.");
        }
        if (empty($this->getVerificationCode()) || is_null($this->verificationCode)) {
            throw new TypeException("Please provide verification code.");
        }

        $this->customer = new Customer($this->customerData);

        $api = new ApiUpdateCardInformation($this, $this->customer);
        $api->handle();

        $this->output = $api->getResult();
    }
}
