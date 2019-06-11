<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Controller;


use Bundle\User\Entity\User;
use Doctrine\DBAL\Logging\EchoSQLLogger;
use Kernel\Annotations\Annotations\Security;
use Kernel\Controller;
use Kernel\Form\Validation\AbstractValidator;
use Kernel\Mailer;
use Kernel\Validation;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $errors = [];

        if ($request->request->has('form')) {
            $form = $request->request->get('form');
            if ($form['password'] === $form['passwordConfirm']) {
                $validator = new Validation();

                $user = new User();
                $user
                    ->setLogin($form['login'])
                    ->setMail($form['mail'])
                    ->setCode(uniqid());
                $user->setPlainPassword($form['password']);

                $errors = $validator->validate($user);

                if (count($errors) === 0) {
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

                    $this->getFlashBag()->add('success',
                        'Un e-mail vous a été envoyé avec un lien de confirmation');
                    return $this->redirectToRoute('homepage');
                }
            } else {
                $this->getFlashBag()->add('danger', 'Les deux mots de passe doivent être identiques');
            }
        }

        return $this->getTemplate()->renderResponse('@User/register.html.twig', [
            'errors' => $errors,
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
        $errors = [];

        if ($request->request->has('form')) {
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
        $errors = [];

        if ($request->request->has('form')) {
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

        if ($request->request->has('form') &&
            $request->request->get('form')['btn'] === 'password') {
            $form = $request->request->get('form');

            if ($form['password'] === $form['passwordConfirm']) {
                $validator = new Validation();
                $em = $this->getEntityManager();

                $user = $this->getSession()->get('user');
                $user->setPlainPassword($form['password']);
                $errors = $validator->validate($user);

                if (count($errors) == 0) {
                    $user->updatePassword();

                    $em->persist($user);
                    $em->flush();

                    $this->getFlashBag()->add(
                        'success',
                        'Changement de mot de passe réussi, par sécurité vous devez vous reconnecter'
                    );

                    return $this->disconnectAction();
                }
            } else {
                $this->getFlashBag()->add(
                    'danger',
                    'Les deux mots de passe ne correspondent pas'
                );
            }
        } else if ($request->request->has('form') &&
        $request->request->get('form')['btn'] === 'mail') {
            $form = $request->request->get('form');

            if ($form['mail'] === $form['mailConfirm']) {
                $validator = new Validation();
                $em = $this->getEntityManager();

                $user = $this->getSession()->get('user');
                $user->setMail($form['mail']);
                $errors = $validator->validate($user);

                if (count($errors) == 0) {
                    $em->persist($user);
                    $em->flush();

                    $this->getFlashBag()->add(
                        'success',
                        "Changement d'e-mail réussi, par sécurité vous devez vous reconnecter"
                    );

                    return $this->disconnectAction();
                }
            } else {
                $this->getFlashBag()->add(
                    'danger',
                    'Les deux e-mail ne correspondent pas'
                );
            }
        } else if ($request->request->has('form') && $request->request->get('form')['btn'] === 'avatar') {
            $form = $request->request->get('form');
            $em = $this->getEntityManager();

            /**
             * @var $uploadedFile UploadedFile
             */
            $uploadedFile = $request->files->get('form')['uploadAvatar'];

            if ($uploadedFile->getMimeType() !== 'image/png' && $uploadedFile->getMimeType() !== 'image/jpeg') {
                $this->getFlashBag()->add(
                    'danger',
                    'Seules les images en PNG ou JPEG sont autorisées'
                    );
            } else {
                $dimensions = getimagesize($uploadedFile->getPathname());

                if ($dimensions[0] > 80) {
                    $this->getFlashBag()->add(
                        'danger',
                        'La largeur doit être de 80 pixels au maximum'
                    );

                    goto template;
                }

                if ($dimensions[1] > 80) {
                    $this->getFlashBag()->add(
                        'danger',
                        'La hauteur doit être de 80 pixels au maximum'
                    );

                    goto template;
                }

                $dir = __DIR__.'/../../../../web/upload/avatar';

                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                /**
                 * @var $user User
                 */
                $user = $this->getSession()->get('user');

                if (!empty($user->getAvatar())) {
                    unlink($dir.'/'.$user->getAvatar());
                }

                $file = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move($dir, $file);
                $user->setAvatar($file);

                $em->merge($user);
                $em->flush();

                $this->getFlashBag()->add(
                  'success',
                  'Votre avatar a été mis à jour avec succès, vous devez vous reconnecter'
                );
                return $this->disconnectAction();
            }
        }

        template:
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
