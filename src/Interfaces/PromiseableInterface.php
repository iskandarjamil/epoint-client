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

interface PromiseableInterface
{
    /**
     * Success responder
     * @param  method $callback function to execute
     *
     * @return void
     */
    public function then($callback = null);

    /**
     * Error responder
     * @param  method $callback function to execute
     *
     * @return void
     */
    public function reject($callback = null);
}
