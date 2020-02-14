<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Model\User\Entity\User;
use App\Tests\Functional\ApiTestCase;

class ResetPasswordControllerTest extends ApiTestCase
{
    private const URI = '/api/auth/reset-password';

    public function test_reset()
    {
        $users = $this->em->getRepository(User::class);
        $user = $users->findOneBy(['email' => 'existed@gmail.com']);

        $response = $this->json('post', self::URI, [
            'email' => 'existed@gmail.com'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('success', json_decode($responseBody, true));
        $this->assertNotNull($user->getResetPasswordToken()->getToken());
    }

}