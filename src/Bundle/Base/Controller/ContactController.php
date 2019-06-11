<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\Base\Controller;


use Bundle\Base\Entity\Contact;
use Kernel\Controller;
use Kernel\Mailer;
use Kernel\Validation;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function contactAction(Request $request)
    {
        $errors = [];

        if ($request->request->has('form')) {
            $form = $request->request->get('form');

            $validator = new Validation();
            $contact = new Contact();
            $contact->setMail($form['mail'])->setObject($form['object'])->setMessage($form['message']);

            $errors = $validator->validate($contact);

            if (count($errors) === 0) {
                /** @var Mailer $mailer */
                $mailer = $this->container->get('mailer');

                $message = $mailer->newMessage(
                    $contact->getObject(),
                    '<p><b>Message envoyé depuis le site ETS2Routes</b></p><p>'.$contact->getMessage().'</p>',
                    'text/html'
                );
                $message
                    ->setTo(
                        $this->container->getParameter('mailer.from'),
                        $this->container->getParameter('mailer.from.name')
                    )
                    ->setFrom($contact->getMail());

                $mailer->send($message);

                $this->getFlashBag()->add('success',
                    'Merci pour votre e-mail, vous recevrez une réponse rapidement');
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->getTemplate()->renderResponse('@Base/contact.html.twig', [
            'errors' => $errors
        ]);
    }
}
