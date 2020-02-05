<?php

namespace App\Controller\Auth;

use App\Infrastructure\Validation\ValidationErrors;
use App\Model\User\UseCase\ConfirmSignUp\ConfirmSignUpCommand;
use App\Model\User\UseCase\ConfirmSignUp\ConfirmSignUpHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConfirmRegistrationController extends AbstractController
{
    public function index(
        Request $request,
        ValidatorInterface $validator,
        ConfirmSignUpHandler $handler
    ) {
        $command = new ConfirmSignUpCommand($request->get('email'), $request->get('confirmToken'));
        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            $errors = new ValidationErrors($errors);
            return $this->json(['errors' => $errors->toArray()]);
        }

        $handler->handle($command);

        return $this->json(['success' => 'Registration confirmed']);
    }
}