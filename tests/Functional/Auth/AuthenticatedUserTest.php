<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Infrastructure\Model\Auth\AccessTokenRepository;
use App\Model\User\Entity\User;
use App\Tests\Functional\ApiTestCase;

class AuthenticatedUserTest extends ApiTestCase
{
    private const URI = '/api/auth/me';

    public function test_unauthenticated()
    {
        $this->client->request('get', self::URI, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'invalid_token'
        ]);
        $response = $this->client->getResponse()->getContent();
        self::assertJson($response);
        self::assertArrayHasKey('error', json_decode($response, true));
    }

    public function test_success()
    {
        $repo = $this->em->getRepository(User::class);

        self::bootKernel();
        $container = self::$container;
        $tokenRepository = $container->get(AccessTokenRepository::class);

        $user = $repo->findOneBy(['email' => 'existed@gmail.com']);
        $token = $tokenRepository->generate($user->getId());

        $this->client->request('get', self::URI, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => $token
        ]);
        $response = $this->client->getResponse();

        self::assertEquals($response->getStatusCode(), 200);
        self::assertJson($response->getContent());
    }
}