<?php

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/** @Embeddable */
class ConfirmToken
{
    /** @Column(type = "string") */
    private $token;

    /** @Column(type = "datetime_immutable", nullable=true) */
    private $expireDate;

    public function __construct(string $token, \DateTimeImmutable $expireDate)
    {
        $this->token = $token;
        $this->expireDate = $expireDate;
    }

    public function isExpired() : bool
    {
        $now = new \DateTimeImmutable();
        return $now >= $this->expireDate;
    }

    public function getToken()
    {
        return $this->token;
    }
}