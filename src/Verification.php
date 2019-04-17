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
use EpointClient\Api\ApiVerificationCard;
use EpointClient\Execption\TypeException;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\RequestRepository;
use EpointClient\Resources\IsDateTimeAble;
use EpointClient\Resources\Request;
use EpointClient\Resources\Respond;

/**
 * Epoint Card verification process
 */
class Verification extends EpointClient
{
    use IsDateTimeAble;

    protected $cardNo;
    protected $verificationCode;
    protected $output;

    public function __construct(string $cardNo = '', string $verificationCode = '')
    {
        parent::__construct();

        $this->setCardNo($cardNo);
        $this->setVerificationCode($verificationCode);
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
        } catch (\Execption $e) {
            throw new \Execption($e->getMessage());
        }

        return true;
    }

    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set Card No
     *
     * @param string $cardNo Card No
     *
     * @return void
     */
    public function setCardNo(string $cardNo)
    {
        $this->cardNo = $cardNo;

        return $this;
    }

    /**
     * Set Verification Code
     *
     * @param string $verificationCode Verification Code
     *
     * @return void
     */
    public function setVerificationCode(string $verificationCode)
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    /**
     * Retrieve Card No
     *
     * @return string
     */
    public function getCardNo()
    {
        return $this->cardNo;
    }

    /**
     * Retrieve Verification Code
     *
     * @return string
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    protected function validate()
    {
        if (empty($this->getCardNo()) || is_null($this->cardNo)) {
            throw new TypeException("Please provide card no.");
        }
        if (empty($this->getVerificationCode()) || is_null($this->verificationCode)) {
            throw new TypeException("Please provide verification code.");
        }

        $api = new ApiVerificationCard();
        $api->setCardNo($this->getCardNo());
        $api->setVerificationCode($this->getVerificationCode());
        $api->handle();

        $this->output = $api->getResult();
    }
}
