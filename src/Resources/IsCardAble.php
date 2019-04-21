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

trait IsCardAble
{
    protected $cardNo;
    protected $verificationCode;

    /**
     * Set Card No
     *
     * @param string $cardNo Card No
     *
     * @return void
     */
    public function setCardNo(string $cardNo)
    {
        $this->cardNo = trim($cardNo);

        return $this;
    }

    /**
     * Set Verification Code
     *
     * @param string $verificationCode Verification Code
     *
     * @return void
     */
    public function setVerificationCode(string $verificationCode)
    {
        $this->verificationCode = trim($verificationCode);

        return $this;
    }

    /**
     * Retrieve Card No
     *
     * @return string
     */
    public function getCardNo()
    {
        return $this->cardNo;
    }

    /**
     * Retrieve Verification Code
     *
     * @return string
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }
}
