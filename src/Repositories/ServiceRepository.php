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

use EpointClient\Interfaces\PromiseableInterface;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Resources\IsPromiseable;
use EpointClient\Resources\Request;

abstract class ServiceRepository implements ServiceInterface, PromiseableInterface
{
    use IsPromiseable;

    protected $parent;
    protected $isError = false;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }
    abstract public function handle();
}
