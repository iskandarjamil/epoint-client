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

trait IsPromiseable
{
    public function then($callback = null)
    {
        $this->validateResponse();

        if ($this->isError === true) {
            return call_user_func($callback, $this->result);
        }

        return $this;
    }
    public function reject($callback = null)
    {
        $this->validateResponse();

        if ($this->isError === false) {
            return call_user_func($callback, $this->result);
        }

        return $this;
    }

    public function validateResponse()
    {
        if ($this->result) {
            if (isset($this->result['status'])) {
                $this->isError = $this->result['status'];
            }
        }

        return $this;
    }
}
