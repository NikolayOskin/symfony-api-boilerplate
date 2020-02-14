<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword;

use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordCommand
{
    /**
     * @Assert\NotBlank
     */
    public $email;
}