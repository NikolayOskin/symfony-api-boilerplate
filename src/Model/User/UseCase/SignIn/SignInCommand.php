<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignIn;

use Symfony\Component\Validator\Constraints as Assert;

class SignInCommand
{
    /**
     * @Assert\NotBlank
     */
    public $email;

    /**
     * @Assert\NotBlank
     */
    public $password;
}