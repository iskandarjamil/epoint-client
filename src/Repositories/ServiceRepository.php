<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient\Repositories;

use EpointClient\Interfaces\PromiseableInterface;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Resources\IsPromiseable;
use EpointClient\Resources\Request;

abstract class ServiceRepository implements ServiceInterface, PromiseableInterface
{
    use IsPromiseable;

    protected $parent;
    protected $epointCard;
    protected $isError = false;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Base rules card validation
     * @return boolean
     */
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

            return false;
        }

        $verify = $this->epointCard->verify($this->getVerificationCode());
        if (is_null($this->epointCard->getVerificationCode())) {
            $this->result = (object) [
                'status' => false,
                'code' => 103,
                'message' => "Unable to determine your verification code.",
            ];

            return false;
        }

        if (!$verify) {
            $this->result = (object) [
                'status' => false,
                'code' => 104,
                'message' => "Your verification code is invalid.",
            ];

            return false;
        }

        return true;
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
