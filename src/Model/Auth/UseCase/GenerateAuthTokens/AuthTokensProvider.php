<?php

declare(strict_types=1);

namespace App\Model\Auth\UseCase\GenerateAuthTokens;

use App\Infrastructure\Model\Auth\AccessTokenRepository;
use App\Model\Auth\Entity\RefreshToken;
use App\Model\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AuthTokensProvider
{
    /**
     * @var AccessTokenRepository
     */
    private $accessTokenRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(AccessTokenRepository $accessTokenRepository, EntityManagerInterface $em)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->em = $em;
    }

    public function createAccessTokenFor(User $user) : string
    {
        return $this->accessTokenRepository->generate($user->getId());
    }

    public function createRefreshTokenFor(User $user) : string
    {
        $refreshToken = new RefreshToken($user);
        $this->em->persist($refreshToken);
        $this->em->flush();

        return $refreshToken->getToken();
    }

}