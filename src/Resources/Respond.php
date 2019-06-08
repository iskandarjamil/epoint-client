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

use EpointClient\Resources\IsDateTimeAble;

class Respond
{
    use IsDateTimeAble;

    protected $respond;
    protected $isError = true;
    protected $result;
    protected $args;

    public function __construct()
    {
    }

    public function success(array $args)
    {
        $this->isError = true;
        $this->args = $args;
        $this->build();
        $this->execute();
    }

    public function error(array $args)
    {
        $this->isError = false;
        $this->args = $args;
        $this->build();
        $this->execute();
    }

    public function build()
    {
        array_map(function ($key) {
            $this->result[$key] = $this->value($key);
        }, array_keys($this->args));

        $this->result['data'] = $this->value('data');
        $this->result['message'] = $this->value('message');
        $this->result['status'] = $this->isError;
        $this->result['dt'] = $this->getCurrentTime();

        return $this;
    }

    public function value(String $key, $default = null)
    {
        return isset($this->args[$key]) ? $this->args[$key] : $default;
    }

    public function execute()
    {
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($this->result);

        exit;
    }
}
