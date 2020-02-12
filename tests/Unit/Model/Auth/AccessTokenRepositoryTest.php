<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Auth;

use App\Infrastructure\Model\Auth\AccessTokenRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccessTokenRepositoryTest extends KernelTestCase
{
    public function test_encoding_decoding()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $repo = new AccessTokenRepository(
            $container->getParameter('jwt_algorithm'),
            $container->getParameter('jwt_private_key'),
            $container->getParameter('jwt_public_key')
        );

        $userId = '123456qwerty';

        $token = $repo->generate($userId);
        $payload = $repo->getPayloadFromToken($token);

        self::assertEquals($userId, $payload['user_id']);
        self::assertTrue(time() < $payload['exp']);
    }

}