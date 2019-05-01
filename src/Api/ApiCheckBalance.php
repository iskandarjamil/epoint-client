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

use EpointClient\CheckBalance;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\EpointRepository;
use EpointClient\Repositories\ServiceRepository;
use EpointClient\Repositories\UserRepository;
use EpointClient\Verification;

class ApiCheckBalance extends ServiceRepository
{
    protected $parent;
    protected $epointCard;

    public function __construct(CheckBalance $parent)
    {
        $this->parent = $parent;
    }

    public function handle()
    {
        if (!parent::handle()) {
            return $this;
        }

        /**
         * Success
         */
        $this->result = (object) [
            'status' => true,
            'code' => 200,
            'message' => "Card information retrieved.",
            'data' => $this->epointCard->getWallet()
        ];

        return $this;
    }
}
