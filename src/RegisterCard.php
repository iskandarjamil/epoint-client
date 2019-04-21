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

use EpointClient\Api\ApiRegisterCard;
use EpointClient\Customer;
use EpointClient\Execption\TypeException;
use EpointClient\Interfaces\CardInterface;
use EpointClient\Resources\IsCardAble;
use EpointClient\Resources\IsDateTimeAble;

/**
 * Epoint Card registration process
 */
class RegisterCard extends EpointClient implements CardInterface
{
    use IsDateTimeAble;
    use IsCardAble;

    protected $customer;
    protected $customerData = [];
    protected $output;

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

    /**
     * Execute registration card process.
     *
     * @return void
     * @throws Exception
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

    public function isRegistered()
    {
        if (is_null($this->getOutput())) {
            throw new TypeException("Please run `execute` method, before checking validation result.");
        }

        return $this->output->code === 200;
    }

    /**
     * Retrieve output.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Retrieve output status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->output->status;
    }

    /**
     * Retrieve output status code.
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->output->code;
    }

    /**
     * Retrieve output message.
     *
     * @return string
     */
    public function getError()
    {
        return $this->output->message;
    }

    /**
     * Retrieve output status.
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->output->message;
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

        $api = new ApiRegisterCard($this, $this->customer);
        $api->handle();

        $this->output = $api->getResult();
    }
}
