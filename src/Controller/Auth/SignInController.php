<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Infrastructure\Validation\ValidationErrors;
use App\Model\User\UseCase\SignIn\SignInCommand;
use App\Model\User\UseCase\SignIn\SignInHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param SignInHandler $handler
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        SignInHandler $handler,
        Request $request
    ) {
        /** @var SignInCommand $command */
        $command = $serializer->deserialize($request->getContent(), SignInCommand::class, 'json');

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            $errors = new ValidationErrors($errors);
            return $this->json(['errors' => $errors->toArray()], 422);
        }

        $token = $handler->handle($command);

        return $this->json(['access_token' => $token], 200);
    }

}