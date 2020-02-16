<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignIn;

use App\Infrastructure\Model\Auth\AccessTokenRepository;
use App\Infrastructure\Model\User\Entity\UserRepository;
use App\Infrastructure\Model\User\Service\PasswordHasher;
use App\Model\User\Entity\Email;

class CredentialsCheckerHandler
{
    private $tokenRepository;
    private $userRepository;
    /**
     * @var PasswordHasher
     */
    private $hasher;

    public function __construct(
        AccessTokenRepository $tokenRepository,
        UserRepository $userRepository,
        PasswordHasher $hasher
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function handle(CredentialsCheckerCommand $command) : void
    {
        if (!$user = $this->userRepository->getByEmail(Email::createFromString($command->email))) {
            throw new \DomainException("Invalid credentials");
        }
        if (!$this->hasher->isValid($command->password, $user->getPasswordHash())) {
            throw new \DomainException("Invalid credentials");
        }
    }
}