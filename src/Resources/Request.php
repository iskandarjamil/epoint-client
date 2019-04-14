<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient\Resources;

class Request
{
    protected $implementation;

    public function __construct(array $implementation)
    {
        $this->implementation = $this->sanitize($implementation);
    }

    protected function sanitize(array $implementation)
    {
        return array_map(function ($val) {
            return htmlspecialchars($val, ENT_QUOTES);
        }, $implementation);
    }

    public function has(String $key)
    {
        return isset($this->implementation[$key]);
    }

    public function get(String $key)
    {
        if ($this->has($key)) {
            return $this->implementation[$key];
        }
    }

    public function getAll()
    {
        return $this->implementation;
    }
}
