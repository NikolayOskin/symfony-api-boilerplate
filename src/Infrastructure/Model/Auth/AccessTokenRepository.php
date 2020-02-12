<?php

declare(strict_types=1);

namespace App\Infrastructure\Model\Auth;

use Firebase\JWT\JWT;

class AccessTokenRepository
{
    /**
     * @var string
     */
    private $tokenTTL;
    /**
     * @var string
     */
    private $jwtAlgorithm;
    /**
     * @var string
     */
    private $privateKeyPath;
    /**
     * @var string
     */
    private $publicKeyPath;

    public function __construct(
        string $jwtAlgorithm,
        string $privateKeyPath,
        string $publicKeyPath,
        string $tokenTTL = '+1 hour'
    ) {
        $this->tokenTTL = $tokenTTL;
        $this->jwtAlgorithm = $jwtAlgorithm;
        $this->privateKeyPath = $privateKeyPath;
        $this->publicKeyPath = $publicKeyPath;
    }

    public function generate(string $userId): string
    {
        $payload = [
            'user_id' => $userId,
            'exp' => (new \DateTimeImmutable($this->tokenTTL))->getTimestamp()
        ];
        $jwt = JWT::encode($payload, file_get_contents($this->privateKeyPath), $this->jwtAlgorithm);

        return $jwt;
    }

    public function getPayloadFromToken(string $accessToken) : array
    {
        return (array) JWT::decode($accessToken, file_get_contents($this->publicKeyPath), [$this->jwtAlgorithm]);
    }
}