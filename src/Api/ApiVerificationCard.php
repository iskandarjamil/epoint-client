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
use EpointClient\Verification;

class ApiVerificationCard extends ServiceRepository
{
    protected $parent;
    protected $epointCard;

    public function __construct(Verification $parent)
    {
        $this->parent = $parent;
    }

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
            'message' => "Your card has been verified and is valid.",
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
        return $this->parent->getCardNo();
    }

    public function getVerificationCode()
    {
        return $this->parent->getVerificationCode();
    }

    public function getEpointCard($useCache = true)
    {
        if ($useCache === true) {
            if (!is_null($this->epointCard)) {
                return $this->epointCard;
            }
        }

        $this->epointCard = new EpointRepository($this->getCardNo());

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
