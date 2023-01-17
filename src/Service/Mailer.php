<?php

namespace App\services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $token)
    {
        $email = (new TemplatedEmail())
            ->from('noreplay@solway.com')
            ->to(new Address($email))
            ->subject('validation de votre compte chez SOLWAY Consulting & Services')

            // path of the Twig template to render
            ->htmlTemplate('emails/test.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'token' => $token,
            ]);

        $this->mailer->send($email);
    }
}
