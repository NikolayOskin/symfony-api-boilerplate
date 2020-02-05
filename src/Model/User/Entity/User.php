<?php

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use Webmozart\Assert\Assert;

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
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=128)
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

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    private $updatedAt;

    public function __construct(
        UserId $userId,
        Email $email,
        string $passwordHash,
        ConfirmToken $confirmToken
    ) {
        Assert::notEmpty($email);
        Assert::notEmpty($passwordHash);
        $this->id = $userId->asString();
        $this->email = $email->asString();
        $this->passwordHash = $passwordHash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function confirmSignUp(string $token, \DateTimeImmutable $dateTime) : void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }
        $this->confirmToken->validate($token, $dateTime);
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }
}
