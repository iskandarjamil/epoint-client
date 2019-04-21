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
use EpointClient\Api\ApiTopupCard;
use EpointClient\Data\Topup;
use EpointClient\Exception\TypeException;
use EpointClient\Interfaces\CardInterface;
use EpointClient\Resources\IsCardAble;
use EpointClient\Resources\IsDateTimeAble;
use EpointClient\Resources\IsResultAble;

/**
 * Epoint Card registration process
 */
class TopupCard extends EpointClient implements CardInterface
{
    use IsDateTimeAble;
    use IsCardAble;
    use IsResultAble;

    protected $topup;
    protected $topupData = [];

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

    public function addTransaction(array $topup)
    {
        $this->topupData = $topup;
    }

    public function getTopup()
    {
        return $this->topup;
    }

    public function get()
    {
        return $this->output->data;
    }

    public function isAccepted()
    {
        $response = $this->output->data;

        if (!isset($response->stored_value_balance)) {
            return false;
        }

        if ($response->value_adjusted !== $this->topup->getAmountAdjusted()) {
            return false;
        }

        return true;
    }

    protected function validate()
    {
        if (empty($this->getCardNo()) || is_null($this->cardNo)) {
            throw new TypeException("Please provide card no.");
        }
        if (empty($this->getVerificationCode()) || is_null($this->verificationCode)) {
            throw new TypeException("Please provide verification code.");
        }

        $this->topup = new Topup($this->topupData);

        $api = new ApiTopupCard($this, $this->topup);
        $api->handle();

        $this->output = $api->getResult();
    }
}
