<?php

namespace App\Service;

use App\ValueObject\ContactForm;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailSender
{
    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param ContactForm $contactForm
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendContactForm(ContactForm $contactForm)  : void
    {
        $email = (new TemplatedEmail())
            ->to('zimix@op.pl')
            ->from('zimi1124@gmail.com')
            ->subject('You Got Mail')
            ->htmlTemplate('emails/contact_form.html.twig')
            ->context([
                'name' => $contactForm->name,
                'customer_email' => $contactForm->email,
                'subject' => $contactForm->subject,
                'message' => $contactForm->message,
            ])  ;


            $this->mailer->send($email);

    }
}

