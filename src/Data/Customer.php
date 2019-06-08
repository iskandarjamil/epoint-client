<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient\Data;

use EpointClient\Exception\TypeException;
use EpointClient\Repositories\DataRepository;

/**
 * Defined valid user information to be
 * integrated with Epoint System
 */
class Customer extends DataRepository
{
    protected $input;
    protected $customerFirstName;
    protected $customerLastName;
    protected $customerEmail;
    protected $customerPhone;
    protected $customerDateOfBirth;
    protected $customerGender;
    protected $addressLine1;
    protected $addressLine2;
    protected $addressCity;
    protected $addressPostalCode;
    protected $addressState;
    protected $addressCountry;

    /**
     * Initialize
     */
    public function __construct(array $customer)
    {
        $this->input = $customer;

        try {
            $this->assignInput();
            $this->validate();
            $this->assignData();
        } catch (TypeException $e) {
            throw new TypeException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getFullAddress()
    {
        $address = $this->data->address;
        $buildAddress = "";

        $buildAddress .= $address->line_1 . ", ";
        if (!empty($address->line_2)) {
            $buildAddress .= $address->line_2 . ", ";
        }
        $buildAddress .= $address->postal_code . " ";
        $buildAddress .= $address->city . ", ";
        $buildAddress .= $address->state . ", ";
        $buildAddress .= $address->country;

        return $buildAddress;
    }

    /**
     * Check user information
     *
     * @return void
     */
    protected function validate()
    {
        if (empty($this->getCustomerFirstName()) || is_null($this->getCustomerFirstName())) {
            throw new TypeException("Customer first name is required.");
        }
        if (empty($this->getCustomerLastName()) || is_null($this->getCustomerLastName())) {
            throw new TypeException("Customer last name is required.");
        }
        if (empty($this->getCustomerEmail()) || is_null($this->getCustomerEmail())) {
            throw new TypeException("Customer email is required.");
        }

        return $this;
    }

    protected function assignInput()
    {
        if (!isset($this->input['first_name'])) {
            throw new TypeException("Customer first name is required.");
        }
        if (!isset($this->input['last_name'])) {
            throw new TypeException("Customer last name is required.");
        }
        if (!isset($this->input['email'])) {
            throw new TypeException("Customer email is required.");
        }
        if (!isset($this->input['phone'])) {
            throw new TypeException("Customer phone is required.");
        }

        $this->setCustomerFirstName($this->input['first_name']);
        $this->setCustomerLastName($this->input['last_name']);
        $this->setCustomerEmail($this->input['email']);
        $this->setCustomerPhone($this->input['phone']);
        $this->setCustomerDateOfBirth($this->input['date_of_birth'] ?? null);
        $this->setAddressLine1($this->input['address_line_1'] ?? null);
        $this->setAddressLine2($this->input['address_line_2'] ?? null);
        $this->setAddressCity($this->input['address_city'] ?? null);
        $this->setAddressPostalCode($this->input['address_postal_code'] ?? null);
        $this->setAddressState($this->input['address_state'] ?? null);
        $this->setAddressCountry($this->input['address_country'] ?? null);

        return $this;
    }

    protected function assignData()
    {
        $this->data = (object) [
            'first_name' => $this->getCustomerFirstName(),
            'last_name' => $this->getCustomerLastName(),
            'full_name' => $this->getCustomerFirstName() . " " . $this->getCustomerLastName(),
            'email' => $this->getCustomerEmail(),
            'phone' => $this->getCustomerPhone(),
            'date_of_birth' => $this->getCustomerDateOfBirth(),
            'gender' => $this->getCustomerGender(),
            'address' => (object) [
                'line_1' => $this->getAddressLine1(),
                'line_2' => $this->getAddressLine2(),
                'city' => $this->getAddressCity(),
                'postal_code' => $this->getAddressPostalCode(),
                'state' => $this->getAddressState(),
                'country' => $this->getAddressCountry(),
            ],
        ];

        return $this;
    }

    /**
     * Set first name
     *
     * @param string $customerFirstName first name
     *
     * @return void
     */
    public function setCustomerFirstName(string $customerFirstName)
    {
        $this->customerFirstName = trim($customerFirstName);

        return $this;
    }

    /**
     * Set last name
     *
     * @param string $customerLastName last name
     *
     * @return void
     */
    public function setCustomerLastName(string $customerLastName)
    {
        $this->customerLastName = trim($customerLastName);

        return $this;
    }

    /**
     * Set email
     *
     * @param string $customerEmail email
     *
     * @return void
     */
    public function setCustomerEmail(string $customerEmail)
    {
        $this->customerEmail = strtolower(trim($customerEmail));

        return $this;
    }

    /**
     * Set phone
     *
     * @param string $customerPhone phone number
     *
     * @return void
     */
    public function setCustomerPhone(string $customerPhone)
    {
        $customerPhone = preg_replace('/[^0-9]+/', '', $customerPhone);
        $this->customerPhone = trim($customerPhone);

        return $this;
    }

    /**
     * Set date of birth
     *
     * @param date $customerDateOfBirth date of birth
     *
     * @return void
     */
    public function setCustomerDateOfBirth(string $customerDateOfBirth = null)
    {
        $customerDateOfBirth = trim($customerDateOfBirth);
        $timestamp = strtotime($customerDateOfBirth);
        $year = date("Y", $timestamp);
        $month = date("m", $timestamp);
        $day = date("d", $timestamp);

        if (!checkdate($month, $day, $year)) {
            throw new TypeException("Provided `date of birth: {$customerDateOfBirth}` is not a valid date format. Sample `1991-10-25`");
        }

        $this->customerDateOfBirth = date("Y-m-d", $timestamp);

        return $this;
    }

    /**
     * Set gender
     *
     * @param string $customerGender gender
     *
     * @return void
     */
    public function setCustomerGender(string $customerGender = null)
    {
        $customerGender = trim($customerGender);

        switch ($customerGender) {
            case 'Male':
            case 'male':
            case 'm':
            case 'M':
                $customerGender = "M";
                break;
            case 'Female':
            case 'female':
            case 'f':
            case 'F':
                $customerGender = "F";
                break;
        }

        $this->customerGender = $customerGender;

        return $this;
    }

    /**
     * Set address line 1
     *
     * @param string $addressLine1 address line 1
     *
     * @return void
     */
    public function setAddressLine1(string $addressLine1 = null)
    {
        $this->addressLine1 = trim($addressLine1);

        return $this;
    }

    /**
     * Set address address line 2
     *
     * @param string $addressLine2 address line 2
     *
     * @return void
     */
    public function setAddressLine2(string $addressLine2 = null)
    {
        $this->addressLine2 = trim($addressLine2);

        return $this;
    }

    /**
     * Set address city
     *
     * @param string $addressCity address city
     *
     * @return void
     */
    public function setAddressCity(string $addressCity = null)
    {
        $this->addressCity = trim($addressCity);

        return $this;
    }

    /**
     * Set address postal code
     *
     * @param string $addressPostalCode address postal code
     *
     * @return void
     */
    public function setAddressPostalCode(int $addressPostalCode = null)
    {
        $this->addressPostalCode = trim($addressPostalCode);
    }

    /**
     * Set address state
     *
     * @param string $addressPostalCode address state
     *
     * @return void
     */
    public function setAddressState(string $addressState = null)
    {
        $this->addressState = trim($addressState);
    }

    /**
     * Set address country
     *
     * @param string $addressCountry address country
     *
     * @return void
     */
    public function setAddressCountry(string $addressCountry = null)
    {
        $this->addressCountry = trim($addressCountry);
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
     * Retrieve Gender
     *
     * @return string
     */
    public function getCustomerGender()
    {
        return $this->customerGender;
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

    /**
     * Retrieve State
     *
     * @return string
     */
    public function getAddressState()
    {
        return $this->addressState;
    }

    /**
     * Retrieve Country
     *
     * @return string
     */
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }
}
