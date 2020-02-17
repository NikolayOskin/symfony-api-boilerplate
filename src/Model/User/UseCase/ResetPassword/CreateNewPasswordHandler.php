<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword;

use App\Infrastructure\Model\User\Entity\UserRepository;
use App\Infrastructure\Model\User\Service\PasswordHasher;
use App\Model\User\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;

class CreateNewPasswordHandler
{
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PasswordHasher
     */
    private $hasher;

    public function __construct(
        UserRepository $users,
        EntityManagerInterface $entityManager,
        PasswordHasher $hasher
    ) {
        $this->users = $users;
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }
    
    public function handle(CreateNewPasswordCommand $command) : void
    {
        if (!$user = $this->users->getByEmail(Email::createFromString($command->email))) {
            throw new \DomainException('User not found');
        }
        if ($user->getResetPasswordToken()->getToken() !== $command->token) {
            throw new \DomainException('Incorrect token');
        }
        $user->setNewPassword($this->hasher->hash($command->password));
        $this->entityManager->flush();
    }
}