<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Controller;


use Bundle\User\Entity\User;
use Bundle\User\Validator\RegisterValidator;
use Kernel\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        $validator = new RegisterValidator($request);
        var_dump($validator->validate());
        var_dump($validator->getErrors());

        /*if ($request->request->has('form')) {
            $form = $request->request->get('form');

            $user = new User();
            $user
                ->setLogin($form['login'])
                ->setMail($form['mail']);

            if ($form['password'] === $form['password_confirm']) {
                $user->setPlainPassword($form['password']);
            }

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
        }*/

        return $this->getTemplate()->renderResponse('@User/register.html.twig', [
            'errors' => $validator->getErrors(),
            'form' => $request->request->get('form', []),
        ]);
    }
}
