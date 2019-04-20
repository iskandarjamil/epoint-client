<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient\Api;

use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\EpointRepository;
use EpointClient\Repositories\ServiceRepository;
use EpointClient\Repositories\UserRepository;

class ApiVerificationCard extends ServiceRepository
{
    protected $epointCard;

    public function handle()
    {
        if (!$this->isValidCardNo($this->getCardNo())) {
            $this->result = (object) [
                'status' => false,
                'code' => 101,
                'message' => "You have entered an invalid card no.",
            ];

            return false;
        }

        $this->getEpointCard();
        if (!$this->epointCard->isValid()) {
            $this->result = (object) [
                'status' => false,
                'code' => 102,
                'message' => "Your card no is invalid.",
            ];

            return $this;
        }

        $verify = $this->epointCard->verify($this->getVerificationCode());
        if (is_null($this->epointCard->getVerificationCode())) {
            $this->result = (object) [
                'status' => false,
                'code' => 103,
                'message' => "Unable to determine your verification code.",
            ];

            return $this;
        }
        if (!$verify) {
            $this->result = (object) [
                'status' => false,
                'code' => 104,
                'message' => "Your verification code is invalid.",
            ];

            return $this;
        }

        /**
         * Success
         */
        $this->result = (object) [
            'status' => true,
            'code' => 200,
            'message' => "Your new TCB Card has been successfully added.",
        ];

        return $this;
    }

    /*
     * Getter
     */

    public function getResult()
    {
        return $this->result;
    }

    public function getCardNo()
    {
        return $this->cardNo;
    }

    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    public function getVars()
    {
        $cardno = trim($this->request->get('cardno'));
        $verification_code = trim($this->request->get('verification_code'));

        $this->vars = compact('cardno', 'verification_code');

        return $this;
    }

    public function getEpointCard()
    {
        if (!is_null($this->epointCard)) {
            return $this->epointCard;
        }

        $this->epointCard = new EpointRepository($this->getCardNo());

        return $this;
    }

    /**
     * Setter
     */

    /**
     * @param string $cardNo
     *
     * @return void
     */
    public function setCardNo(string $cardNo)
    {
        $this->cardNo = $cardNo;

        return $this;
    }

    /**
     * @param string $verificationCode
     *
     * @return void
     */
    public function setVerificationCode(string $verificationCode)
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    /*
     * Checker
     */

    public function isValidCardNo($value)
    {
        return is_numeric($value) && strlen($value) >= 10;
    }
}
