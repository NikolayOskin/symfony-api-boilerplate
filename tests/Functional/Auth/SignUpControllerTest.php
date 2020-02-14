<?php

namespace App\Tests\Functional\Auth;

use App\Tests\Functional\ApiTestCase;

class SignUpControllerTest extends ApiTestCase
{
    private const URI = '/api/auth/signup';

    public function test_success()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'test@gmail.com',
            'password' => 'somepassword'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('success', json_decode($responseBody, true));
    }

    public function test_with_empty_password()
    {
        $response = $this->json('post', self::URI, [
            'email' => 'test@gmail.com',
            'password' => ''
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('errors', json_decode($responseBody, true));
    }

    public function test_with_empty_email()
    {
        $response = $this->json('post', self::URI, [
            'email' => '',
            'password' => '1212121212121'
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($responseBody = $response->getContent());
        $this->assertArrayHasKey('errors', json_decode($responseBody, true));
    }

    public function test_existed()
    {
        $this->json('post', self::URI, [
            'email' => 'test@gmail.com',
            'password' => 'somepassword'
        ]);
        $response = $this->json('post', self::URI, [
            'email' => 'test@gmail.com',
            'password' => 'somepassword'
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }
}