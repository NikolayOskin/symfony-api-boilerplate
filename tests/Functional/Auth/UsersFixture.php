<?php

namespace App\Tests\Functional\Auth;

use App\Infrastructure\Model\User\Service\PasswordHasher;
use App\Model\User\Entity\ConfirmToken;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User(
            UserId::create(),
            Email::createFromString('existed@gmail.com'),
            (new PasswordHasher())->hash('somepass'),
            new ConfirmToken('10555', new \DateTimeImmutable('+1 day'))
        );

        $userWithExpiredToken = new User(
            UserId::create(),
            Email::createFromString('expired@gmail.com'),
            (new PasswordHasher())->hash('somepass'),
            new ConfirmToken('10555', new \DateTimeImmutable())
        );

        $manager->persist($user);
        $manager->persist($userWithExpiredToken);
        $manager->flush();
    }
}