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

trait IsResultAble
{
    protected $output;

    /**
     * Retrieve output.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Retrieve output status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->output->status;
    }

    /**
     * Retrieve output status code.
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->output->code;
    }

    /**
     * Retrieve output message.
     *
     * @return string
     */
    public function getError()
    {
        return $this->output->message;
    }

    /**
     * Retrieve output status.
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->output->message;
    }
}
