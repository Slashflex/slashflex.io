<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerService extends AbstractController
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @param $token
     * @param $login
     * @param $template
     * @param $to
     */
    public function sendToken($token, $to, $login, $template)
    {
        $message = (new TemplatedEmail())
            ->from($_ENV['DB_EMAIL'])
            ->to($to)
            ->subject('Mail de confirmation')
            ->htmlTemplate('emails/' . $template)
            ->context([
                'token' => $token,
                'login' => $login
            ]);
        $this->mailer->send($message);
    }
}