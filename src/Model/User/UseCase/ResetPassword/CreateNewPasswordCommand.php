<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword;

use Symfony\Component\Validator\Constraints as Assert;

class CreateNewPasswordCommand
{
    /**
     * @Assert\NotBlank
     */
    public $email;

    /**
     * @Assert\NotBlank
     */
    public $password;

    /**
     * @Assert\NotBlank
     */
    public $token;

}