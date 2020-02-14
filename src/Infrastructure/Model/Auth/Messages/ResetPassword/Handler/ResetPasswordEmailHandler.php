<?php

namespace App\Infrastructure\Model\Auth\Messages\ResetPassword\Handler;

use App\Infrastructure\Model\Auth\Messages\ResetPassword\ResetPasswordEmailNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class ResetPasswordEmailHandler implements MessageHandlerInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(ResetPasswordEmailNotification $notification)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($notification->getEmail())
            ->subject('Reset password confirmation')
            ->text('Copy and paste this password reset confirmation token: ' . $notification->getResetPasswordToken()->getToken());

        $this->mailer->send($email);
    }
}