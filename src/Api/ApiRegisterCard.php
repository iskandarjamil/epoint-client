<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient\Api;

use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\CBTLCardRepository;
use EpointClient\Repositories\EpointRepository;
use EpointClient\Repositories\ServiceRepository;
use EpointClient\Repositories\UserRepository;
use EpointClient\Resources\Query;
use EpointClient\Resources\Request;

class ApiRegisterCard extends ServiceRepository
{
    protected $user;
    protected $epointCard;
    protected $cbtlCard;

    public function handle(Request $request)
    {
        $this->request = $request;
        $this->getVars();

        if (!$this->validate()) {
            return $this;
        }

        $this->getEpointCard();
        if (!$this->epointCard->isValid()) {
            $this->result = [
                'status' => false,
                'message' => "Your card no is invalid.",
            ];

            return $this;
        }

        $verify = $this->epointCard->verify($this->getVerificationCode());
        if (!$verify) {
            $this->result = [
                'status' => false,
                'message' => "Your verification code is invalid.",
            ];

            return $this;
        }

        $createEpointUser = $this->epointCard->createUser($this->user);
        if (isset($createEpointUser->error_code) && $createEpointUser->error_code === '1000') {
            $updateEpointUser = $this->epointCard->updateUser($this->user);

            if (isset($updateEpointUser->error_code) && $updateEpointUser->error_code === '1000') {
                $this->result = [
                    'status' => false,
                    'message' => "Unable to capture your information. Please refer administrator error code (001)",
                ];

                return $this;
            }
        }

        $this->getCbtlCard();
        $createCbtlUser = $this->cbtlCard->create($this->epointCard, $this->user);

        /**
         * Success
         */
        $this->result = [
            'status' => true,
            'message' => "Your new TCB Card has been successfully added.",
        ];

        return $this;
    }

    public function validate()
    {
        if (!$this->exists('cardno')) {
            $this->result = [
                'status' => false,
                'message' => "TCB card no. is required.",
            ];

            return false;
        }

        if (!$this->exists('verification_code')) {
            $this->result = [
                'status' => false,
                'message' => "Verification code is required.",
            ];

            return false;
        }

        if (!$this->isValidCardNo($this->getCardNo())) {
            $this->result = [
                'status' => false,
                'message' => "You have entered an invalid TCB card number.",
            ];

            return false;
        }

        if (!$this->isUserLogged()) {
            $this->result = [
                'status' => false,
                'message' => "Please login to your account.",
            ];

            return false;
        }

        if (!$this->isUserExists()) {
            $this->result = [
                'status' => false,
                'message' => "Your account does not exist.",
            ];

            return false;
        }

        return true;
    }

    public function exists(string $type)
    {
        switch ($type) {
            case 'cardno':
            case 'verification_code':
                return $this->request->has($type);
                break;
        }

        return false;
    }

    public function isUserLogged()
    {
        return !is_null(userId());
    }

    public function isUserExists()
    {
        $this->getCurrentUser();

        return $this->user->exists();
    }

    /*
     * Getter
     */

    public function getCardNo()
    {
        return $this->vars['cardno'];
    }
    public function getVerificationCode()
    {
        return $this->vars['verification_code'];
    }

    public function getVars()
    {
        $cardno = trim($this->request->get('cardno'));
        $verification_code = trim($this->request->get('verification_code'));

        $this->vars = compact('cardno', 'verification_code');

        return $this;
    }

    public function getCurrentUser()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $this->user = new UserRepository(userId());

        return $this;
    }

    public function getEpointCard()
    {
        if (!is_null($this->epointCard)) {
            return $this->epointCard;
        }

        $this->epointCard = new EpointRepository($this->getCardNo());

        return $this;
    }

    public function getCbtlCard()
    {
        if (!is_null($this->cbtlCard)) {
            return $this->cbtlCard;
        }

        $this->cbtlCard = new CBTLCardRepository($this->getCardNo());

        return $this;
    }

    /**
     * Setter
     */

    /*
     * Checker
     */

    public function isValidCardNo($value)
    {
        return is_numeric($value) && strlen($value) >= 10;
    }
}
