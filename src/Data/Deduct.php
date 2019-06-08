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
class Deduct extends DataRepository
{
    protected $input;
    protected $amount;
    protected $orderNo;
    protected $receiptNo;

    /**
     * Initialize
     */
    public function __construct(array $data)
    {
        $this->input = $data;

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

    /**
     * Check transaction
     *
     * @return void
     */
    protected function validate()
    {
        if (empty($this->getAmount()) || is_null($this->getAmount())) {
            throw new TypeException("Amount is required.");
        }
        if (empty($this->getOrderNo()) || is_null($this->getOrderNo())) {
            throw new TypeException("Order no is required.");
        }
        if (empty($this->getReceiptNo()) || is_null($this->getReceiptNo())) {
            throw new TypeException("Receipt no is required.");
        }

        return $this;
    }

    protected function assignInput()
    {
        if (!isset($this->input['amount'])) {
            throw new TypeException("Amount is required.");
        }
        if (!isset($this->input['order_no'])) {
            throw new TypeException("Order no is required.");
        }
        if (!isset($this->input['receipt_no'])) {
            throw new TypeException("Receipt no is required.");
        }

        $this->setAmount($this->input['amount']);
        $this->setOrderNo($this->input['order_no']);
        $this->setReceiptNo($this->input['receipt_no']);

        return $this;
    }

    protected function assignData()
    {
        $this->data = (object) [
            'amount' => $this->getAmount(),
            'order_no' => $this->getOrderNo(),
            'receipt_no' => $this->getReceiptNo(),
        ];

        return $this;
    }

    /**
     * Set Amount
     *
     * @param string $amount Amount
     *
     * @return void
     */
    public function setAmount(int $amount)
    {
        $this->amount = trim($amount);

        return $this;
    }

    /**
     * Set Order No
     *
     * @param string $order_no Order no
     *
     * @return void
     */
    public function setOrderNo(string $orderNo)
    {
        $this->orderNo = trim($orderNo);

        return $this;
    }

    /**
     * Set Receipt No
     *
     * @param string $receipt_no Receipt no
     *
     * @return void
     */
    public function setReceiptNo(string $receiptNo)
    {
        $this->receiptNo = trim($receiptNo);

        return $this;
    }

    /**
     * Retrieve Amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Retrieve Amount adjusted
     *
     * @return string
     */
    public function getAmountAdjusted()
    {
        return $this->getAmount() . ".00";
    }

    /**
     * Retrieve Order no
     *
     * @return string
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * Retrieve Receipt no
     *
     * @return string
     */
    public function getReceiptNo()
    {
        return $this->receiptNo;
    }
}
