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

use EpointClient\Data\Customer;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\CBTLCardRepository;
use EpointClient\Repositories\EpointRepository;
use EpointClient\Repositories\ServiceRepository;
use EpointClient\Repositories\UserRepository;
use EpointClient\Resources\Query;
use EpointClient\Resources\Request;
use EpointClient\UpdateCardInformation;

class ApiUpdateCardInformation extends ServiceRepository
{
    protected $parent;
    protected $customer;
    protected $user;
    protected $epointCard;
    protected $cbtlCard;

    public function __construct(UpdateCardInformation $parent, Customer $customer)
    {
        $this->parent = $parent;
        $this->customer = $customer;
    }

    public function handle()
    {
        if (!parent::handle()) {
            return $this;
        }

        $updateEpointUser = $this->epointCard->updateUser($this->customer);
        if (isset($updateEpointUser->error_code) && $updateEpointUser->error_code === '1000') {
            $this->result = (object) [
                'status' => false,
                'code' => 105,
                'message' => "Unable to capture your information. Please refer administrator error code (105).",
            ];

            return $this;
        }

        $this->getEpointCard(false);

        /**
         * Success
         */
        $this->result = (object) [
            'status' => true,
            'code' => 200,
            'message' => "Card has been successfully updated.",
            'data' => $this->epointCard
        ];

        return $this;
    }

    public function exists(string $type)
    {
        switch ($type) {
            case 'cardno':
            case 'verification_code':
                return $this->request->has($type);
                break;
        }

        return false;
    }

    /*
     * Getter
     */

    public function getCustomer()
    {
        return $this->customer;
    }
}
