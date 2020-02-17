<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Infrastructure\Validation\ValidationErrors;
use App\Model\User\UseCase\ResetPassword\CreateNewPasswordCommand;
use App\Model\User\UseCase\ResetPassword\CreateNewPasswordHandler;
use App\Model\User\UseCase\ResetPassword\ResetPasswordCommand;
use App\Model\User\UseCase\ResetPassword\ResetPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param Request $request
     * @param ResetPasswordHandler $handler
     * @return JsonResponse
     */
    public function reset(Request $request, ResetPasswordHandler $handler)
    {
        /*  @var $command ResetPasswordCommand */
        $command = $this->serializer->deserialize($request->getContent(), ResetPasswordCommand::class, 'json');
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            return $this->json(['errors' => (new ValidationErrors($errors))->toArray()], 422);
        }

        $handler->handle($command);

        return $this->json(['success' => 'Confirmation email is sent'], 200);
    }

    /**
     * @Route("/auth/new-password", name="auth.new-password", methods={"POST"})
     * @param Request $request
     * @param CreateNewPasswordHandler $handler
     * @return JsonResponse
     */
    public function confirm(Request $request, CreateNewPasswordHandler $handler)
    {
        /*  @var $command CreateNewPasswordCommand */
        $command = $this->serializer->deserialize($request->getContent(), CreateNewPasswordCommand::class, 'json');
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            return $this->json(['errors' => (new ValidationErrors($errors))->toArray()], 422);
        }

        $handler->handle($command);

        return $this->json(['success' => 'Password has been changed'], 200);
    }
}