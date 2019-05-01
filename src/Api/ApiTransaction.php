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

use EpointClient\Transaction;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\EpointRepository;
use EpointClient\Repositories\ServiceRepository;
use EpointClient\Repositories\UserRepository;
use EpointClient\Verification;

class ApiTransaction extends ServiceRepository
{
    protected $parent;
    protected $epointCard;

    public function __construct(Transaction $parent)
    {
        $this->parent = $parent;
    }

    public function handle()
    {
        if (!parent::handle()) {
            return $this;
        }

        $transaction = $this->epointCard->transaction();
        if (isset($transaction->error_code) && $transaction->error_code === '1000') {
            $this->result = (object) [
                'status' => false,
                'code' => 105,
                'message' => "Unable to capture your transaction information. Please refer administrator error code (105).",
            ];

            return $this;
        }

        /**
         * Success
         */
        $this->result = (object) [
            'status' => true,
            'code' => 200,
            'message' => "Card transaction information retrieved.",
            'data' => isset($transaction->member) ? $transaction->member : $transaction
        ];

        return $this;
    }
}
