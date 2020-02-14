<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Infrastructure\Validation\ValidationErrors;
use App\Model\User\UseCase\ResetPassword\CreateNewPasswordCommand;
use App\Model\User\UseCase\ResetPassword\CreateNewPasswordHandler;
use App\Model\User\UseCase\ResetPassword\ResetPasswordCommand;
use App\Model\User\UseCase\ResetPassword\ResetPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {

        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/auth/reset-password", name="auth.reset", methods={"POST"})
     */
    public function reset(Request $request, ResetPasswordHandler $handler)
    {
        /*  @var $command ResetPasswordCommand */
        $command = $this->serializer->deserialize($request->getContent(), ResetPasswordCommand::class, 'json');

        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            $errors = new ValidationErrors($errors);
            return $this->json(['errors' => $errors->toArray()], 422);
        }

        $handler->handle($command);

        return $this->json(['success' => 'Confirmation email is sent'], 200);

    }

    public function confirm(Request $request, CreateNewPasswordHandler $handler)
    {
        $command = $this->serializer->deserialize($request->getContent(), CreateNewPasswordCommand::class, 'json');

        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            $errors = new ValidationErrors($errors);
            return $this->json(['errors' => $errors->toArray()], 422);
        }

        $handler->handle($command);

        return $this->json(['success' => 'Confirmation email is sent'], 200);

    }
}