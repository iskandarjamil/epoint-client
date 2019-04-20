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

use EpointClient\Repositories\DataRepository;
use EpointClient\Resources\Curl;

class EpointRepository extends DataRepository
{
    protected $token;
    protected $data;
    protected $errors = [];

    public function __construct($id = null)
    {
        if (!is_null($id)) {
            $this->getCard($id);
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        switch ($name) {
            case 'get':
                return $this->data;
                break;
        }

        return $this;
    }

    public function __get($name)
    {
        if ($this->data) {
            if (isset($this->data->{$name})) {
                return $this->data->{$name};
            }
        }

        return false;
    }

    public function verify($verification_code)
    {
        // if (!is_object($this->data)) {
        //     $card = $this->get($this->data);
        // }

        if (is_null($this->data)) {
            return false;
        }

        if (is_null($this->getVerificationCode())) {
            return false;
        }

        if ($verification_code == $this->getVerificationCode()) {
            return true;
        }

        return false;
    }

    public function errors()
    {
        if (sizeof($this->errors) == 0) {
            return false;
        }

        return $this->errors;
    }

    public function createUser(UserRepository $user)
    {
        $primaryAddress = $user->getPrimaryAddress();
        $data = [
            'api_key'     => $this->getEpointApi(),
            'outlet_id'   => $this->getEpointStoreId(),
            'loyaltycard' => $this->getCardId(),
            'm'           => 'update_member',
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name,
            'full_name'   => $user->full_name,
            'mobile'      => $user->contact,
            'email'       => $user->email,
            'dob'         => $user->dob,
            'city'        => $primaryAddress ? trim($primaryAddress->city) . ", " . trim($primaryAddress->statename) : null,
            'zip'         => $primaryAddress ? trim($primaryAddress->postcode) : null,
            'address1'    => $primaryAddress ? trim($primaryAddress->address1) : null,
            'address2'    => $primaryAddress ? trim($primaryAddress->address2) : null,
        ];

        $response = (new Curl())
            ->url($this->getEntryPoint())
            ->data($data, 'json')
            ->wrapper("data")
            ->isPost()
            ->run();
        $response = json_decode($response);

        $this->setErrors($response);

        return $response;
    }

    public function updateUser(UserRepository $user)
    {
        $primaryAddress = $user->getPrimaryAddress();
        $data = [
            'api_key'     => $this->getEpointApi(),
            'outlet_id'   => $this->getEpointStoreId(),
            'loyaltycard' => $this->getCardId(),
            'm'           => 'update_member',
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name,
            'full_name'   => $user->full_name,
            'info1'       => $user->contact,
            'info2'       => $user->email,
            'dob'         => $user->dob,
            'city'        => $primaryAddress ? trim($primaryAddress->city) . ", " . trim($primaryAddress->statename) : null,
            'zip'         => $primaryAddress ? trim($primaryAddress->postcode) : null,
            'address1'    => $primaryAddress ? trim($primaryAddress->address1) : null,
            'address2'    => $primaryAddress ? trim($primaryAddress->address2) : null,
        ];

        $response = (new Curl())
            ->url($this->getEntryPoint())
            ->data($data, 'json')
            ->wrapper("data")
            ->isPost()
            ->run();
        $response = json_decode($response);

        $this->setErrors($response);


        return $response;
    }

    public function isValid()
    {
        return !is_null($this->data);
    }

    public function hasValidResponse($response)
    {
        $response = isset($response->member) ? current($response) : $response;
        // $response = is_array($response) && isset($response->error_code) ? $response : current($response);

        if (!isset($response->error_code)) {
            return false;
        }

        if ($response->error_code != '200') {
            return false;
        }

        return true;
    }

    protected function setErrors($response)
    {
        if (isset($response->remarks)) {
            $this->errors[] = $response->remarks;
        } elseif (isset($response->status)) {
            $this->errors[] = $response->status;
        }

        return $this;
    }

    protected function getCard($card_no)
    {
        $data = [
            'api_key'            => $this->getEpointApi(),
            'outlet_id'          => $this->getEpointStoreId(),
            'loyaltycard'        => $card_no,
            'm'                  => 'enquire_member',
            'transaction_record' => 'NO',
            'voucher_list'       => 'NO',
        ];

        $response = (new Curl())
            ->url($this->getEntryPoint())
            ->data($data, 'json')
            ->wrapper("data")
            ->isPost()
            ->run();
        $response = json_decode($response);

        $this->setErrors($response);

        if (!$this->hasValidResponse($response)) {
            return false;
        }

        $this->data = current($response);

        return $this;
    }

    public function getVerificationCode()
    {
        if (isset($this->data->wallets)) {
            $wallet = current($this->data->wallets);
        } else {
            return null;
        }

        return isset($wallet->verification_code) ? $wallet->verification_code : null;
    }

    public function getCardId()
    {
        if (isset($this->data->wallets)) {
            $wallet = current($this->data->wallets);
        } else {
            return null;
        }

        return $wallet->card_id;
    }

    protected function getEntryPoint()
    {
        return $this->getEpointUrl() . "?format=json&hash=". $this->getEpointHash() . "&db_name=" . $this->getEpointDb();
    }

    protected function getEpointUrl()
    {
        return EPOINT_ENTRY_POINT;
    }

    protected function getEpointHash()
    {
        return md5(EPOINT_PASSWORD);
    }

    protected function getEpointDb()
    {
        return EPOINT_DB;
    }

    protected function getEpointApi()
    {
        return EPOINT_USERNAME;
    }

    protected function getEpointStoreId()
    {
        return EPOINT_STORE_ID;
    }
}
