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

use EpointClient\Resources\IsDateTimeAble;

abstract class DataRepository
{
    use IsDateTimeAble;

    protected $data;

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

            if (is_array($this->data)) {
                if (isset($this->data[$name])) {
                    return $this->data[$name];
                }
            }
        }

        return false;
    }
}
