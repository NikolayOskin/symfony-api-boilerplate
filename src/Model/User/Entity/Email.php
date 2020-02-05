<?php

namespace App\Model\User\Entity;

use Webmozart\Assert\Assert;

class Email
{
    private $email;

    public static function createFromString(string $email) : Email
    {
        Assert::stringNotEmpty($email);
        return new self($email);
    }

    private function __construct(string $email)
    {
        $this->email = mb_strtolower($email);
    }

    public function asString() : string
    {
        return $this->email;
    }
}