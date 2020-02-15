<?php

namespace App\Controller\Auth;

use App\Infrastructure\Validation\ValidationErrors;
use App\Model\User\UseCase\ConfirmSignUp\ConfirmSignUpCommand;
use App\Model\User\UseCase\ConfirmSignUp\ConfirmSignUpHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConfirmRegistrationController extends AbstractController
{
    /**
     * @Route("/auth/confirm", name="auth.confirm", methods={"POST"})
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param ConfirmSignUpHandler $handler
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function index(
        Request $request,
        ValidatorInterface $validator,
        ConfirmSignUpHandler $handler,
        SerializerInterface $serializer
    ) {
        /** @var ConfirmSignUpCommand $command */
        $command = $serializer->deserialize($request->getContent(), ConfirmSignUpCommand::class, 'json');
        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return $this->json(['errors' => (new ValidationErrors($errors))->toArray()], 422);
        }

        $handler->handle($command);

        return $this->json(['success' => 'Registration confirmed'], 200);
    }
}