<?php

namespace App\Infrastructure\Model\User\Entity;

use App\Model\User\Entity\User;
use App\Model\User\Entity\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    private $repo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repo = $entityManager->getRepository(User::class);
    }

    public function hasByEmail(string $email) : bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function getByEmail(string $email): User
    {
        if (!$user = $this->repo->findOneBy(['email' => $email])) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }

}