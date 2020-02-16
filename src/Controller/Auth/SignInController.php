<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Infrastructure\Model\User\Entity\UserRepository;
use App\Infrastructure\Validation\ValidationErrors;
use App\Model\Auth\UseCase\GenerateAuthTokens\AuthTokensProvider;
use App\Model\User\Entity\Email;
use App\Model\User\UseCase\SignIn\CredentialsCheckerCommand;
use App\Model\User\UseCase\SignIn\CredentialsCheckerHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignInController extends AbstractController
{
    /**
     * @Route("/auth/signin", name="auth.signin", methods={"POST"})
     *
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param CredentialsCheckerHandler $handler
     * @param UserRepository $userRepository
     * @param AuthTokensProvider $tokensProvider
     * @param Request $request
     * @return JsonResponse
     */
    public function index(
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        CredentialsCheckerHandler $handler,
        UserRepository $userRepository,
        AuthTokensProvider $tokensProvider,
        Request $request
    ) {
        /** @var CredentialsCheckerCommand $command */
        $command = $serializer->deserialize($request->getContent(), CredentialsCheckerCommand::class, 'json');
        if (count($errors = $validator->validate($command)) > 0) {
            return $this->json(['errors' => (new ValidationErrors($errors))->toArray()], 422);
        }
        $handler->handle($command);
        $user = $userRepository->getByEmail(Email::createFromString($command->email));

        return $this->json([
            'access_token' => $tokensProvider->createAccessTokenFor($user),
            'refresh_token' => $tokensProvider->createRefreshTokenFor($user)
        ], 200);
    }
}