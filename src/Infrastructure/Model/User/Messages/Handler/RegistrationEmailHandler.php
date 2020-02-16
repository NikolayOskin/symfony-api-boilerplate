<?php

namespace App\Infrastructure\Model\User\Messages\Handler;

use App\Infrastructure\Model\User\Messages\RegistrationEmailNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class RegistrationEmailHandler implements MessageHandlerInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(RegistrationEmailNotification $notification)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($notification->getEmail())
            ->subject('Welcome!')
            ->text('Thanks for registration in our service. Please verify your email with this token: ' . $notification->getConfirmToken()->getToken());

        $this->mailer->send($email);

    }
}