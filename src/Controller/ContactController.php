<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\EmailSender;
use App\ValueObject\ContactForm;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['GET','POST'])]
    public function index(Request $request, EmailSender $mailerSender ,LoggerInterface $logger): Response
    {

        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        $successMessage = null;

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ContactForm $contactForm */
            $contactForm = $form->getData();



            try {
                $mailerSender->sendContactForm($contactForm);
                $successMessage = 'Your message has been sent.';
            }
            catch (TransportExceptionInterface $exception) {
                $form->addError(new FormError('Something went wrong'));
                $logger->error('It was not possible to send the email',[
                    'error' => $exception->getMessage(),
                ]);
            }


        }

        return $this->render('widget/contact.html.twig', [
            'form' => $form,
            'successMessage' => $successMessage,
        ]);
    }
}
