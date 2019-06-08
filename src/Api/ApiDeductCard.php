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

use EpointClient\Data\Deduct;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\EpointRepository;
use EpointClient\Repositories\ServiceRepository;
use EpointClient\Repositories\UserRepository;
use EpointClient\DeductCard;

class ApiDeductCard extends ServiceRepository
{
    protected $parent;
    protected $deduct;
    protected $epointCard;

    public function __construct(DeductCard $parent, Deduct $deduct)
    {
        $this->parent = $parent;
        $this->deduct = $deduct;
    }

    public function handle()
    {
        if (!parent::handle()) {
            return $this;
        }

        $deduct = $this->epointCard->deduct($this->deduct);
        if (isset($deduct->error_code) && $deduct->error_code === '1000') {
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
            'message' => "Your card has successfully deduct.",
            'data' => $deduct
        ];

        return $this;
    }
}
