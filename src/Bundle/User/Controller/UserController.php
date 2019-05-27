<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Controller;


use Bundle\User\Entity\User;
use Bundle\User\Validator\LoginValidator;
use Bundle\User\Validator\LostValidator;
use Bundle\User\Validator\MailValidator;
use Bundle\User\Validator\ProfilePasswordValidator;
use Bundle\User\Validator\RegisterValidator;
use Kernel\Annotations\Annotations\Security;
use Kernel\Controller;
use Kernel\Form\Validation\AbstractValidator;
use Kernel\Mailer;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        $validator = new RegisterValidator($request);
        if ($request->request->has('form') && $validator->validate()) {
            $form = $request->request->get('form');
            $user = new User();
            $user
                ->setLogin($form['login'])
                ->setMail($form['mail'])
                ->setCode(uniqid());
            $user->setPlainPassword($form['password']);

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();

            /** @var Mailer $mailer */
            $mailer = $this->container->get('mailer');

            $message = $mailer->newMessage(
                "Confirmation d'inscription",
                $this->getTemplate()->getRenderer()->render(
                    '@User/validation/register.html.twig', [
                        'user' => $user,
                    ]
                ),
                'text/html'
            );
            $message
                ->setFrom(
                    $this->container->getParameter('mailer.from'),
                    $this->container->getParameter('mailer.from.name')
                )
                ->setTo($user->getMail(), $user->getLogin());

            $mailer->send($message);

            $this->getFlashBag()->add('success', 'Un e-mail vous a été envoyé avec un lien de confirmation');
            return $this->redirectToRoute('homepage');
        }

        return $this->getTemplate()->renderResponse('@User/register.html.twig', [
            'errors' => $validator->getErrors(),
            'form' => $request->request->get('form', []),
        ]);
    }

    /**
     * @param string $code
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function validationAction(string $code)
    {
        $repos = $this->getEntityManager()->getRepository(User::class);
        $user = $repos->findOneBy(['code' => $code]);

        if (is_null($user)) {
            $this->getFlashBag()->add('danger', 'Le code de validation <b>'.$code."</b> n'existe pas");

            return $this->redirectToRoute('homepage');
        }

        if ($user->getActive()) {
            $this->getFlashBag()->add(
                'warning',
                'Le compte utilisateur <b>'.$user->getLogin().'</b> est déjà activé'
            );
            return $this->redirectToRoute('homepage');
        }

        $user->setActive(true);
        $user->setCode('');
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        $this->getFlashBag()->add(
            'success',
            'Le compte utilisateur <b>'.$user->getLogin().'</b> est bien activé'
        );

        return $this->redirectToRoute('homepage');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function loginAction(Request $request)
    {
        $validator = new LoginValidator($request);
        $errors = [];

        if ($request->request->has('form') && $validator->validate()) {
            $form = $request->request->get('form');
            $em = $this->getEntityManager();
            $repos = $em->getRepository(User::class);
            $user = $repos->findOneBy(['login' => $form['login']]);

            if (is_null($user)) {
                $this->getFlashBag()->add('danger', 'Identifiants invalide');
                $errors['login'][] = "Le nom d'utilisateur n'existe pas";
                goto template;
            }

            if (password_verify($form['password'], $user->getPassword())) {
                $user->setLastConnection(new \DateTime());

                $em->persist($user);
                $em->flush();

                $this->getSession()->set('user', $user);
                $this->getFlashBag()->add(
                    'success',
                    'Bienvenue <b>'.$user->getLogin()."</b>, c'est un plaisir de vous revoir"
                );

                return $this->redirectToRoute('homepage');
            } else {
                $this->getFlashBag()->add('danger', 'Identifiants invalide');
                $errors['password'][] = "Le mot de passe ne correspond pas";
            }
        } else {
            $errors = $validator->getErrors();
        }

        template:
        return $this->getTemplate()->renderResponse('@User/login.html.twig', [
            'errors' => $errors,
            'form' => $request->request->get('form', []),
        ]);
    }

    /**
     * Disconnect user
     *
     * @throws \Exception
     * @Security(type="IS_USER", message="Vous devez être identifier pour accéder à cette page", route="homepage")
     */
    public function disconnectAction()
    {
        $this->getSession()->remove('user');
        $this->getSession()->invalidate(0);
        $this->anonymousUser();

        $this->getFlashBag()->add(
            'success',
            'Votre session a été supprimée, vous êtes désormais un utilisateur anonyme'
        );

        return $this->redirectToRoute('homepage');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function lostAction(Request $request)
    {
        $validator = new LostValidator($request);
        $errors = [];

        if ($request->request->has('form') && $validator->validate()) {
            $form = $request->request->get('form');
            $em = $this->getEntityManager();
            $repos = $em->getRepository(User::class);
            $user = $repos->findOneBy(['login' => $form['login'], 'mail' => $form['mail']]);

            if (is_null($user)) {
                $this->getFlashBag()->add(
                    'danger',
                    "Le login et l'adresse e-mail, ne correspondent pas à un utilisateur"
                );
            }

            $password = $this->generatePassword();
            $user->setPlainPassword($password);
            $user->updatePassword();

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();

            /** @var Mailer $mailer */
            $mailer = $this->container->get('mailer');

            $message = $mailer->newMessage(
                "Nouveau mot de passe",
                $this->getTemplate()->getRenderer()->render(
                    '@User/mail/lost.html.twig', [
                        'user' => $user,
                        'password' => $password
                    ]
                ),
                'text/html'
            );
            $message
                ->setFrom(
                    $this->container->getParameter('mailer.from'),
                    $this->container->getParameter('mailer.from.name')
                )
                ->setTo($user->getMail(), $user->getLogin());

            $mailer->send($message);

            $this->getFlashBag()->add('success', 'Un e-mail vous a été envoyé avec le nouveau mot de passe');
            return $this->redirectToRoute('homepage');
        }

        return $this->getTemplate()->renderResponse('@User/lost.html.twig', [
            'errors' => $errors,
            'form' => $request->request->get('form', []),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     *
     * @Security(type="IS_USER", message="Vous devez être identifier pour accéder à cette page", route="homepage")
     */
    public function profileAction(Request $request)
    {
        $errors = [];
        $ProfilePasswordValidator = new ProfilePasswordValidator($request);
        $profileMailValidator = new MailValidator($request);

        if ($request->request->has('form') &&
            $request->request->get('form')['btn'] === 'password' &&
            $ProfilePasswordValidator->validate()) {
            $errors = $ProfilePasswordValidator->getErrors();
            $em = $this->getEntityManager();

            $user = $this->getSession()->get('user');
            $user->setPlainPassword($request->request->has('form')['password']);
            $user->updatePassword();

            $em->persist($user);
            $em->flush();

            $this->getFlashBag()->add(
                'success',
                'Changement de mot de passe réussi, par sécurité vous devez vous reconnecter'
            );

            return $this->disconnectAction();
        } else if ($request->request->has('form') &&
        $request->request->get('form')['btn'] === 'mail' &&
            $profileMailValidator->validate()) {
            $errors = $profileMailValidator->getErrors();
            $em = $this->getEntityManager();

            $user = $this->getSession()->get('user');
            $user->setMail($request->request->has('form')['mail']);

            $em->persist($user);
            $em->flush();

            $this->getFlashBag()->add(
                'success',
                "Changement d'e-mail réussi, par sécurité vous devez vous reconnecter"
            );

            return $this->disconnectAction();
        }

        return $this->getTemplate()->renderResponse('@User/profile.html.twig', [
            'errors' => $errors
        ]);
    }

    /**
     * Generate random secure password
     *
     * @return bool|string
     */
    private function generatePassword()
    {
        start:
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $result = substr(str_shuffle($chars),0,12);

        if (filter_var($result, FILTER_VALIDATE_REGEXP, [
            'options' => [
                'regexp' => AbstractValidator::$REGEXP_PASSWORD
                ]
            ]) === false) {
            goto start;
        }

        return $result;
    }
}
