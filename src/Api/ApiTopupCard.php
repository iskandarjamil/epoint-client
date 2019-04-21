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

use EpointClient\Data\Topup;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\EpointRepository;
use EpointClient\Repositories\ServiceRepository;
use EpointClient\Repositories\UserRepository;
use EpointClient\TopupCard;

class ApiTopupCard extends ServiceRepository
{
    protected $parent;
    protected $topup;
    protected $epointCard;

    public function __construct(TopupCard $parent, Topup $topup)
    {
        $this->parent = $parent;
        $this->topup = $topup;
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

        $topup = $this->epointCard->topup($this->topup);
        if (isset($topup->error_code) && $topup->error_code === '1000') {
            $this->result = (object) [
                'status' => false,
                'code' => 105,
                'message' => "Unable to capture your information. Please refer administrator error code (105).",
            ];

            return $this;
        }

        $this->getEpointCard(true);

        /**
         * Success
         */
        $this->result = (object) [
            'status' => true,
            'code' => 200,
            'message' => "Your card has successfully topup.",
            'data' => $topup
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
