<?php

namespace App\Model\User\UseCase\ConfirmSignUp;

use Symfony\Component\Validator\Constraints as Assert;

class ConfirmSignUpCommand
{
    /**
     * @Assert\NotBlank
     */
    public $email;

    /**
     * @Assert\NotBlank
     */
    public $confirmToken;
}