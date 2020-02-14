<?php

namespace App\Model\User\UseCase\ConfirmSignUp;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ConfirmSignUpHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $repo;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        UserRepositoryInterface $repo,
        EntityManagerInterface $entityManager
    ) {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }

    public function handle(ConfirmSignUpCommand $command) : void
    {
        $user = $this->repo->getByEmail(Email::createFromString($command->email));
        if (!$user) {
            throw new \DomainException('User not found.');
        }

        $user->confirmSignUp($command->confirmToken, new \DateTimeImmutable());

        $this->entityManager->flush();
    }

}