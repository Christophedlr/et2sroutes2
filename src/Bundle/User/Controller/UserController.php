<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Controller;


use Bundle\User\Entity\User;
use Bundle\User\Validator\RegisterValidator;
use Kernel\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        $validator = new RegisterValidator($request);
        if ($request->request->has('form') && $validator->validate()) {
            $form = $request->request->get('form');
            $user = new User();
            $user
                ->setLogin($form['login'])
                ->setMail($form['mail']);
            $user->setPlainPassword($form['password']);

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();

            $this->getFlashBag()->add('success', 'Un e-mail vous a été envoyé avec un lien de confirmation');
            return $this->redirectToRoute('homepage');
        }

        return $this->getTemplate()->renderResponse('@User/register.html.twig', [
            'errors' => $validator->getErrors(),
            'form' => $request->request->get('form', []),
        ]);
    }
}
