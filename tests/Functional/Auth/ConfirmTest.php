<?php

namespace App\Tests\Functional\Auth;

use App\Tests\Functional\ApiTestCase;

class ConfirmTest extends ApiTestCase
{
    private const URI = '/api/auth/confirm';

    public function test_success()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'existed@gmail.com',
            'confirmToken' => '10555'
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('success', json_decode($responseBody, true));
    }

    public function test_token_expired()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'expired@gmail.com',
            'confirmToken' => '10555'
        ]);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
    }

    public function test_user_not_found()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'notexisted@gmail.com',
            'confirmToken' => '10555'
        ]);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
    }

    public function test_token_is_null()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'tester@gmail.com'
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('errors', json_decode($responseBody, true));
    }

    public function test_email_is_null()
    {
        $response = $this->json('post', self::URI, [
            'confirmToken' => 11111
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('errors', json_decode($responseBody, true));
    }
}