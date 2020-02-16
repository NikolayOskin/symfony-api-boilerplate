<?php

declare(strict_types=1);

namespace App\Model\Auth\Entity;

use App\Model\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="refresh_tokens")
 */
class RefreshToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=128)
     */
    private $refreshToken;

    /**
     * @ManyToOne(targetEntity="App\Model\User\Entity\User")
     * @JoinColumn(name="user_id", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", name="expired_at", nullable=false)
     */
    private $expiredAt;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private $createdAt;

    public function __construct(User $user)
    {
        $this->refreshToken = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->expiredAt = new \DateTimeImmutable('+30 days');
        $this->createdAt = new \DateTimeImmutable();
    }

    public function isExpired() : bool
    {
        return (new \DateTimeImmutable()) >= $this->expiredAt;
    }

    public function getToken() : string
    {
        return $this->refreshToken;
    }

    public function user() : User
    {
        return $this->user;
    }
}