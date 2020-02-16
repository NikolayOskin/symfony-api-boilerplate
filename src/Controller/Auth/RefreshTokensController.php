<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Infrastructure\Validation\ValidationErrors;
use App\Model\Auth\Entity\RefreshToken;
use App\Model\Auth\UseCase\GenerateAuthTokens\AuthTokensProvider;
use App\Model\Auth\UseCase\RefreshTokens\RefreshTokensCommand;
use App\Model\Auth\UseCase\RefreshTokens\RefreshTokensHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RefreshTokensController extends AbstractController
{
    /**
     * @Route("/auth/refresh", name="auth.refresh", methods={"POST"})
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param AuthTokensProvider $tokensProvider
     * @param Request $request
     * @param RefreshTokensHandler $handler
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AuthTokensProvider $tokensProvider,
        Request $request,
        RefreshTokensHandler $handler
    ) {
        /** @var RefreshTokensCommand $command */
        $command = $serializer->deserialize($request->getContent(), RefreshTokensCommand::class, 'json');
        if (count($errors = $validator->validate($command)) > 0) {
            return $this->json(['errors' => (new ValidationErrors($errors))->toArray()], 422);
        }
        $handler->handle($command);

        /** @var RefreshToken $refreshToken */
        $refreshToken = $em->getRepository(RefreshToken::class)->find($command->refreshToken);

        return $this->json([
            'access_token' => $tokensProvider->createAccessTokenFor($refreshToken->user()),
            'refresh_token' => $tokensProvider->createRefreshTokenFor($refreshToken->user())
        ], 200);
    }
}