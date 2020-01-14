<?php

namespace App\Tests\Unit\Model\User\Entity;

use App\Model\User\Entity\ConfirmToken;
use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_create_user_entity()
    {
        $user = new User(
            $email = 'example@gmail.com',
            $hash = 'hash',
            $token = new ConfirmToken('token', new \DateTimeImmutable())
        );

        self::assertEquals($user->getEmail(), $email);
        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
        self::assertEquals($user->getConfirmToken(), $token);
    }

    public function test_confirm_user_sign_up()
    {
        $user = new User(
            $email = 'example@gmail.com',
            $hash = 'hash',
            $token = new ConfirmToken('token', new \DateTimeImmutable('+1 day'))
        );
        $user->confirmSignUp($token->getToken(), new \DateTimeImmutable());
        self::assertTrue($user->isActive());
        self::assertEquals($user->getConfirmToken(), null);
    }
}