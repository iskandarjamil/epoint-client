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

trait IsDateTimeAble
{
    protected $currentTime;

    public function hasCurrentTime()
    {
        return !is_null($this->currentTime);
    }

    public function getCurrentTime()
    {
        if (!$this->hasCurrentTime()) {
            $this->setCurrentTime();
        }

        return $this->currentTime;
    }

    public function setCurrentTime()
    {
        $this->currentTime = date("Y-m-d H:i:s");

        return $this;
    }
}
