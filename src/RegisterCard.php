<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient;

use EpointClient\Api\ApiRegisterCard;
use EpointClient\Execption\TypeException;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\RequestRepository;
use EpointClient\Resources\IsDateTimeAble;
use EpointClient\Resources\Request;
use EpointClient\Resources\Respond;

/**
 * Epoint Card registration process
 */
class RegisterCard
{
    use IsDateTimeAble;

    protected $cardNo;
    protected $verificationCode;
    protected $customerFirstName;
    protected $customerLastName;
    protected $customerEmail;
    protected $customerPhone;
    protected $customerDateOfBirth;
    protected $addressLine1;
    protected $addressLine2;
    protected $addressCity;
    protected $addressPostalCode;

    /**
     * Execute registration card process.
     *
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        try {
            $this->validate();
        } catch (TypeException $e) {
            throw new TypeException($e->getMessage());
        }

        return true;
    }

    /**
     * Set Card No
     *
     * @param string $cardNo Card No
     *
     * @return void
     */
    public function setCardNo(string $cardNo)
    {
        return $this->cardNo;
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
        return $this->verificationCode;
    }

    /**
     * Set Card No
     *
     * @param string $customerFirstName Card No
     *
     * @return void
     */
    public function setCustomerFirstName(string $customerFirstName)
    {
        return $this->customerFirstName;
    }

    /**
     * Set Card No
     *
     * @param string $customerLastName Card No
     *
     * @return void
     */
    public function setCustomerLastName(string $customerLastName)
    {
        return $this->customerLastName;
    }

    /**
     * Set Card No
     *
     * @param string $customerEmail Card No
     *
     * @return void
     */
    public function setCustomerEmail(string $customerEmail)
    {
        return $this->customerEmail;
    }

    /**
     * Set Card No
     *
     * @param string $customerPhone Card No
     *
     * @return void
     */
    public function setCustomerPhone(string $customerPhone)
    {
        return $this->customerPhone;
    }

    /**
     * Set Card No
     *
     * @param string $customerDateOfBirth Card No
     *
     * @return void
     */
    public function setCustomerDateOfBirth(string $customerDateOfBirth)
    {
        return $this->customerDateOfBirth;
    }

    /**
     * Set address line 1
     *
     * @param string $addressLine1 address line 1
     *
     * @return void
     */
    public function setAddressLine1(string $addressLine1)
    {
        return $this->addressLine1;
    }

    /**
     * Set address address line 2
     *
     * @param string $addressLine2 address line 2
     *
     * @return void
     */
    public function setAddressLine2(string $addressLine2)
    {
        return $this->addressLine2;
    }

    /**
     * Set address city
     *
     * @param string $addressCity address city
     *
     * @return void
     */
    public function setAddressCity(string $addressCity)
    {
        return $this->addressCity;
    }

    /**
     * Set address postal code
     *
     * @param string $addressPostalCode address postal code
     *
     * @return void
     */
    public function setAddressPostalCode(string $addressPostalCode)
    {
        return $this->addressPostalCode;
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

    /**
     * Retrieve Customer First Name
     *
     * @return string
     */
    public function getCustomerFirstName()
    {
        return $this->customerFirstName;
    }

    /**
     * Retrieve Customer Last Name
     *
     * @return string
     */
    public function getCustomerLastName()
    {
        return $this->customerLastName;
    }

    /**
     * Retrieve Customer Email
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Retrieve Customer Phone
     *
     * @return string
     */
    public function getCustomerPhone()
    {
        return $this->customerPhone;
    }

    /**
     * Retrieve Date of Birth
     *
     * @return string
     */
    public function getCustomerDateOfBirth()
    {
        return $this->customerDateOfBirth;
    }

    /**
     * Retrieve Address Line 1
     *
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * Retrieve Address Line 2
     *
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * Retrieve Address City
     *
     * @return string
     */
    public function getAddressCity()
    {
        return $this->addressCity;
    }

    /**
     * Retrieve Postal Code
     *
     * @return string
     */
    public function getAddressPostalCode()
    {
        return $this->addressPostalCode;
    }

    protected function validate()
    {
        if (empty($this->getCardNo()) || is_null($this->getCardNo)) {
            # code...
        }
    }
}
