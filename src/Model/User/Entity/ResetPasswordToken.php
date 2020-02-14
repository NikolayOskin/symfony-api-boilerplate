<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/** @Embeddable */
class ResetPasswordToken
{
    /** @Column(type = "string", length=30, nullable=true) */
    private $token;

    public function __construct()
    {
        $this->token = (string) rand(1000000000, 999999999999);
    }

    public function getToken() : string
    {
        return $this->token;
    }
}