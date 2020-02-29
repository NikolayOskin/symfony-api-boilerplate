<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Model\User\Entity\User;
use App\Tests\Functional\ApiTestCase;

class ResetPasswordControllerTest extends ApiTestCase
{
    private const RESET_PASSWORD_URI = '/api/auth/reset-password';
    private const NEW_PASSWORD_URI = '/api/auth/new-password';

    public function test_reset()
    {
        $users = $this->em->getRepository(User::class);
        /** @var User $user */
        $user = $users->findOneBy(['email' => 'existed@gmail.com']);

        $response = $this->json('post', self::RESET_PASSWORD_URI, [
            'email' => 'existed@gmail.com'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('success', json_decode($responseBody, true));
        $this->assertNotNull($user->getResetPasswordToken()->getToken());
    }

    public function test_reset_with_empty_data()
    {
        $response = $this->json('post', self::RESET_PASSWORD_URI, []);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function test_new_password_with_incorrect_token()
    {
        $users = $this->em->getRepository(User::class);
        $users->findOneBy(['email' => 'existed@gmail.com']);

        $response = $this->json('post', self::NEW_PASSWORD_URI, [
            'email' => 'existed@gmail.com',
            'token' => '34234234',
            'password' => 'somenewpass'
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}