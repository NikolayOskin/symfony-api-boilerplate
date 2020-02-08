<?php

namespace App\Tests\Unit\Model\User\Entity;

use App\Model\User\Entity\ConfirmToken;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserId;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_create_user_entity()
    {
        $user = new User(
            UserId::create(),
            Email::createFromString('some@gmail.com'),
            $hash = 'hash',
            $token = new ConfirmToken('token', new \DateTimeImmutable())
        );

        self::assertEquals($user->getEmail(), Email::createFromString('some@gmail.com')->asString());
        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
        self::assertEquals($user->getConfirmToken(), $token);
    }

    public function test_confirm_user_sign_up()
    {
        $user = new User(
            UserId::create(),
            Email::createFromString('some@gmail.com'),
            $hash = 'hash',
            $token = new ConfirmToken('token', new \DateTimeImmutable('+1 day'))
        );
        $user->confirmSignUp($token->getToken(), new \DateTimeImmutable());
        self::assertTrue($user->isActive());
        self::assertEquals($user->getConfirmToken(), null);
    }

    public function test_confirm_signup_with_expired_token_throws_exception()
    {
        self::expectException(\DomainException::class);

        $user = new User(
            UserId::create(),
            Email::createFromString('some@gmail.com'),
            $hash = 'hash',
            $token = new ConfirmToken('token', new \DateTimeImmutable())
        );
        $user->confirmSignUp($token->getToken(), new \DateTimeImmutable());
    }

    public function test_confirm_signup_with_invalid_token_throws_exception()
    {
        self::expectException(\DomainException::class);

        $user = new User(
            UserId::create(),
            Email::createFromString('some@gmail.com'),
            $hash = 'hash',
            $token = new ConfirmToken('12345', new \DateTimeImmutable('+1 day'))
        );
        $user->confirmSignUp('1234', new \DateTimeImmutable());

    }
}