<?php

namespace App\Controller\Auth;

use App\Infrastructure\Validation\ValidationErrors;
use App\Model\User\UseCase\SignUp\SignUpCommand;
use App\Model\User\UseCase\SignUp\SignUpHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpController extends AbstractController
{
    /**
     * @Route("/auth/signup", name="auth.signup", methods={"POST"})
     *
     * @param SignUpHandler $handler
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(
        SignUpHandler $handler,
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        /** @var SignUpCommand $command */
        $command = $serializer->deserialize($request->getContent(), SignUpCommand::class, 'json');

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            $errors = new ValidationErrors($errors);
            return $this->json(['errors' => $errors->toArray()], 422);
        }

        $handler->handle($command);

        return $this->json(['success' => $command->email], 200);
    }
}