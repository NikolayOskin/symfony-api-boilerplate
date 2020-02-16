<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Functional\ApiTestCase;

class SignInControllerTest extends ApiTestCase
{
    private const URI = '/api/auth/signin';

    public function test_success()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'existed@gmail.com',
            'password' => 'somepass',
        ]);

        $this->assertJson($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('access_token', json_decode($response->getContent(), true));
        $this->assertArrayHasKey('refresh_token', json_decode($response->getContent(), true));
    }

    public function test_invalid_credentials()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'existed@gmail.com',
            'password' => 'somepass1',
        ]);

        $this->assertJson($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', json_decode($response->getContent(), true));
    }
}