<?php

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

/** @Embeddable */
class ConfirmToken
{
    /** @Column(type = "string", nullable=true) */
    private $token;

    /** @Column(type = "datetime_immutable", nullable=true) */
    private $expireDate;

    public function __construct(string $token, \DateTimeImmutable $expireDate)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expireDate = $expireDate;
    }

    public function validate(string $token, \DateTimeImmutable $dateTime)
    {
        if ($this->isExpired($dateTime)) {
            throw new \DomainException('Token is already expired');
        }
        if (!$this->isEqualTo($token)) {
            throw new \DomainException('Token is invalid');
        }
    }

    public function isEqualTo(string $token) : bool
    {
        return $this->token === $token;
    }

    public function isExpired(\DateTimeImmutable $dateTime) : bool
    {
        return $dateTime >= $this->expireDate;
    }

    public function getToken(): string
    {
        return $this->token;
    }


}