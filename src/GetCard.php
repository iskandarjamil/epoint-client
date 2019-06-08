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

use EpointClient\Api\ApiGetCard;
use EpointClient\Exception\TypeException;
use EpointClient\Interfaces\CardInterface;
use EpointClient\Resources\IsCardAble;
use EpointClient\Resources\IsDateTimeAble;
use EpointClient\Resources\IsResultAble;

/**
 * Epoint Card registration process
 */
class GetCard extends EpointClient implements CardInterface
{
    use IsDateTimeAble;
    use IsCardAble;
    use IsResultAble;

    protected $customer;
    protected $customerData = [];

    public function __construct(string $cardNo = '', string $verificationCode = '')
    {
        parent::__construct();

        $this->setCardNo($cardNo);
        $this->setVerificationCode($verificationCode);
    }

    /**
     * Execute get card.
     *
     * @return void
     * @throws TypeException
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

    public function get()
    {
        return $this->output->data;
    }

    public function getWallet()
    {
        $card = $this->get();
        $wallets = $card->wallets;

        return current($wallets);
    }

    public function isValid()
    {
        if (is_null($this->getOutput())) {
            throw new TypeException("Please run `execute` method, before checking validation result.");
        }

        return $this->output->code === 200;
    }

    protected function validate()
    {
        if (empty($this->getCardNo()) || is_null($this->cardNo)) {
            throw new TypeException("Please provide card no.");
        }
        if (empty($this->getVerificationCode()) || is_null($this->verificationCode)) {
            throw new TypeException("Please provide verification code.");
        }

        $api = new ApiGetCard($this);
        $api->handle();

        $this->output = $api->getResult();
    }
}
