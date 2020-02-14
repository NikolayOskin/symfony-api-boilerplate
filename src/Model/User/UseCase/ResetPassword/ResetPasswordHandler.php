<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword;

use App\Infrastructure\Model\Auth\Messages\ResetPassword\ResetPasswordEmailNotification;
use App\Infrastructure\Model\User\Entity\UserRepository;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\ResetPasswordToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ResetPasswordHandler
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(
        UserRepository $repository,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->bus = $bus;
    }

    public function handle(ResetPasswordCommand $command)
    {
        if (!$user = $this->repository->getByEmail(Email::createFromString($command->email))) {
            throw new \DomainException('Email is not exist');
        }
        $user->setResetPasswordToken(new ResetPasswordToken());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->bus->dispatch(new ResetPasswordEmailNotification($user));
    }

}