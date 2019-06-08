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
use EpointClient\Resources\Query;

class UserRepository extends DataRepository
{
    protected $data;

    public function __construct($id = null)
    {
        if (!is_null($id)) {
            $this->getUser($id);
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

            switch ($name) {
                case 'first_name':
                    return $this->data->fname;
                    break;
                case 'last_name':
                    return $this->data->lname;
                    break;
                case 'full_name':
                    return $this->data->fname . " " . $this->data->lname;
                    break;
            }
        }

        return false;
    }

    public function create()
    {
        return $this;
    }

    public function remove()
    {
        return $this;
    }

    public function update()
    {
        return $this;
    }

    public function exists()
    {
        return !is_null($this->data);
    }

    public function address()
    {
        if (isset($this->data->address)) {
            return $this->data->address;
        }

        $query = (new Query("SELECT b.`name` AS `statename`, a.* FROM `" . _PREFIX_ . "user_add` a JOIN `" . _PREFIX_ . "states` b ON a.`state`=b.`id` WHERE a.`user_id`=? ORDER BY a.`id`;"))->bind('s', $this->data->id)->get();
        $this->data->address = $query;

        if (!is_null($this->data->address)) {
            array_map(function ($q) {
                $q->faddress = $this->getFullAddress($q);
            }, $this->data->address);
        }

        return $this->data->address;
    }

    public function getUser($user_id)
    {
        $this->data = (new Query("SELECT * FROM `" . _PREFIX_ . "user` WHERE `id`=?;"))->bind('s', $user_id)->get();
        $this->data = $this->data;

        return $this;
    }

    public function getUserId()
    {
        return $this->data->id;
    }

    public function getFullAddress($address)
    {
        $str = "";
        $str .= $address->address1 . ", ";
        if (!empty($address->address2)) {
            $str .= $address->address2 . ", ";
        }
        $str .= $address->postcode . " ";
        $str .= $address->city . ", ";
        $str .= $address->statename;

        return $str;
    }

    public function getPrimaryAddress()
    {
        if (!isset($this->data->address)) {
            $this->address();
        }

        if (!$this->data->address) {
            return null;
        }

        $address = array_filter($this->data->address, function ($q) {
            return $q->primary == true;
        });
        $address = current($address);

        return $address;
    }
}
