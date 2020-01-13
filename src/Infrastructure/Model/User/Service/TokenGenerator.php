<?php

namespace App\Infrastructure\Model\User\Service;

use App\Model\User\Entity\ConfirmToken;

class TokenGenerator
{
    public function generate() : ConfirmToken
    {
        $randomString = (string) rand(10000, 99999);
        return new ConfirmToken($randomString, (new \DateTimeImmutable())->modify('+1 day'));
    }

}