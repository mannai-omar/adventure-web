<?php

namespace App\Services;

use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * MailerService constructor.
     *
     * @param MailerInterface       $mailer
     * @param Environment   $twig
     */
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $template
     * @param array $parameters
     * @throws TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function send(string $subject, string $from, string $to, string $template,$qrCode,$id): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->htmlTemplate($template)
                ->context([
                    'qrCode' => $qrCode,
                    'id' => $id
                 ]);
            $this->mailer->send($email);
        } catch (TransportException $e) {
            print $e->getMessage()."\n";
            throw $e;
        }

    }
}