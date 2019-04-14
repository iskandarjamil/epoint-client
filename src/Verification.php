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

    public function __construct(string $cardNo, string $verificationCode)
    {
        parent::__construct();
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

    /**
     * Set Card No
     *
     * @param string $cardNo Card No
     *
     * @return void
     */
    public function setCardNo(string $cardNo)
    {
        return $this->cardNo;
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
        return $this->verificationCode;
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
        if (empty($this->getCardNo()) || is_null($this->getCardNo)) {
            throw new TypeException("Please provide card no.");
        }
        if (empty($this->getCardNo()) || is_null($this->getCardNo)) {
            throw new TypeException("Please provide verification code.");
        }
    }
}
