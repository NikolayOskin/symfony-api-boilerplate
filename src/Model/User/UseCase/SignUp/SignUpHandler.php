<?php

namespace App\Model\User\UseCase\SignUp;

use App\Infrastructure\Model\User\Entity\UserRepository;
use App\Infrastructure\Model\User\Messages\RegistrationEmailNotification;
use App\Infrastructure\Model\User\Service\PasswordHasher;
use App\Infrastructure\Model\User\Service\ConfirmTokenGenerator;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SignUpHandler
{
    /**
     * @var PasswordHasher
     */
    private $hasher;
    /**
     * @var ConfirmTokenGenerator
     */
    private $tokenizer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(
        PasswordHasher $hasher,
        ConfirmTokenGenerator $tokenizer,
        EntityManagerInterface $entityManager,
        UserRepository $users,
        MessageBusInterface $bus
    ) {
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->entityManager = $entityManager;
        $this->users = $users;
        $this->bus = $bus;
    }

    public function handle(SignUpCommand $command) : void
    {
        if ($this->users->hasByEmail($command->email)) {
            throw new \DomainException('User with this email already exists.');
        }
        $user = new User(
            UserId::create(),
            Email::createFromString($command->email),
            $this->hasher->hash($command->password),
            $this->tokenizer->generate()
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->bus->dispatch(new RegistrationEmailNotification($user));
    }
}