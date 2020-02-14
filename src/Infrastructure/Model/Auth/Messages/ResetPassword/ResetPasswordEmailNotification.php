<?php

namespace App\Infrastructure\Model\Auth\Messages\ResetPassword;

use App\Model\User\Entity\ConfirmToken;
use App\Model\User\Entity\ResetPasswordToken;
use App\Model\User\Entity\User;

class ResetPasswordEmailNotification
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getEmail() : string
    {
        return $this->user->getEmail();
    }

    public function getResetPasswordToken() : ResetPasswordToken
    {
        return $this->user->getResetPasswordToken();
    }
}