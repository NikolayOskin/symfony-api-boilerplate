<?php

declare(strict_types=1);

namespace App\Model\Auth\UseCase\RefreshTokens;

use App\Model\Auth\Entity\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;

class RefreshTokensHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(RefreshTokensCommand $command) : void
    {
        $repo = $this->entityManager->getRepository(RefreshToken::class);

        if (!$refreshToken = $repo->find($command->refreshToken)) {
            throw new \DomainException('Unauthenticated');
        }
        if ($refreshToken->isExpired()) {
            throw new \DomainException('Refresh token is expired');
        }
    }

}