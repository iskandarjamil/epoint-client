<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient\Interfaces;

use EpointClient\Resources\Request;

interface CurlInterface
{
    public function hasToken();
    public function getToken();
}
