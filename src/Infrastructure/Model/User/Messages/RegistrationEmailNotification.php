<?php

namespace App\Infrastructure\Model\User\Messages;

use App\Model\User\Entity\ConfirmToken;
use App\Model\User\Entity\User;

class RegistrationEmailNotification
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

    public function getConfirmToken() : ConfirmToken
    {
        return $this->user->getConfirmToken();
    }
}