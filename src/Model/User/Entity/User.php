<?php

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;

/**
 * @ORM\Entity
 * @ORM\Table(name="users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 */
class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /** @Embedded(class = "ConfirmToken") */
    private $confirmToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="password_hash")
     */
    private $passwordHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="status")
     */
    private $status;

    public function __construct(string $email, string $passwordHash, ConfirmToken $confirmToken)
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
