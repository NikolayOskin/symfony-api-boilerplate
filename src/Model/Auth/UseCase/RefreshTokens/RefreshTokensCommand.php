<?php

declare(strict_types=1);

namespace App\Model\Auth\UseCase\RefreshTokens;

use Symfony\Component\Validator\Constraints as Assert;

class RefreshTokensCommand
{
    /**
     * @Assert\NotBlank
     */
    public $refreshToken;
}