<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\News\Controller;


use Bundle\News\Entity\News;
use Bundle\News\Entity\NewsCategory;
use Bundle\News\Validator\ChangeValidator;
use Bundle\News\Validator\CreateValidator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Kernel\Controller;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{
    public function returnNewsAction()
    {
        $repos = $this->getEntityManager()->getRepository(News::class);

        return $this->getTemplate()->getRenderer()->render('@News/return_news.html.twig', [
            'news' =>$repos->findAll(),
        ]);
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
    public function createAction(Request $request)
    {
        if (is_null($this->getSession()->get('user')->getId())) {
            $this->getFlashBag()->add('danger', 'Vous devez être identifié pour accéder à cette page');
            return $this->redirectToRoute('homepage');
        }

        $repos = $this->getEntityManager()->getRepository(NewsCategory::class);
        $categories = $repos->findAll();

        $errors = [];
        $form = [];
        $createValidator = new CreateValidator($request);

        if ($request->request->has('form') && $createValidator->validate()) {
            $form = $request->request->get('form');
            $category = $repos->find($form['category']);
            $user = $this->getSession()->get('user');

            $news = new News();
            $news
                ->setName($form['name'])
                ->setCategory($category)
                ->setAuthor($user)
                ->setText($form['text']);

            $em = $this->getEntityManager();
            $em->merge($news);
            $em->flush();

            $this->getFlashBag()->add('success','La news a bien été créée');

            return $this->redirectToRoute('homepage');
        } else {
            $erros = $createValidator->getErrors();
        }

        return $this->getTemplate()->renderResponse('@News/create.html.twig', [
            'errors' => $errors,
            'categories' => $categories,
            'form' => $form
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function changeAction(int $id, Request $request)
    {
        if (is_null($this->getSession()->get('user')->getId())) {
            $this->getFlashBag()->add('danger', 'Vous devez être identifié pour accéder à cette page');
            return $this->redirectToRoute('homepage');
        }

        $repos = $this->getEntityManager()->getRepository(NewsCategory::class);
        $categories = $repos->findAll();

        $reposNews = $this->getEntityManager()->getRepository(News::class);
        $news = $reposNews->find($id);

        $form['name'] = $news->getName();
        $form['text'] = $news->getText();
        $form['category'] = $news->getCategory()->getId();
        $form['slug'] = $news->getSlug();

        $errors = [];
        $validator = new ChangeValidator($request);

        if ($request->request->has('form') && $validator->validate()) {
            $form = $request->request->get('form');
            $category = $repos->find($form['category']);

            $news
                ->setName($form['name'])
                ->setCategory($category)
                ->setText($form['text'])
                ->setSlug($form['slug']);

            $em = $this->getEntityManager();
            $em->persist($news);
            $em->flush();

            $this->getFlashBag()->add('success','La news a bien été modifiée');

            return $this->redirectToRoute('homepage');
        } else {
            $errors = $validator->getErrors();
        }

        return $this->getTemplate()->renderResponse('@News/change.html.twig', [
            'errors' => $errors,
            'categories' => $categories,
            'form' => $form,
            'id' => $id,
        ]);
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws ORMException
     * @throws \Exception
     */
    public function deleteAction(int $id)
    {
        if (is_null($this->getSession()->get('user')->getId())) {
            $this->getFlashBag()->add('danger', 'Vous devez être identifié pour accéder à cette page');
            return $this->redirectToRoute('homepage');
        }

        $reposNews = $this->getEntityManager()->getRepository(News::class);
        $news = $reposNews->find($id);

        $em = $this->getEntityManager();
        $em->remove($news);
        try {
            $em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
            $this->getFlashBag()->add('danger', 'Impossible de supprimer la news');
        }

        $this->getFlashBag()->add('success', 'La news a bien été supprimée');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function listingAction()
    {
        if (is_null($this->getSession()->get('user')->getId())) {
            $this->getFlashBag()->add('danger', 'Vous devez être identifié pour accéder à cette page');
            return $this->redirectToRoute('homepage');
        }

        $repos = $this->getEntityManager()->getRepository(News::class);

        return $this->getTemplate()->renderResponse('@News/listing.html.twig', [
            'news' => $repos->findBy([], ['id' => 'ASC']),
        ]);
    }
}
