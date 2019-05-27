<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\News\Controller;


use Bundle\News\Entity\NewsCategory;
use Bundle\News\Validator\Category\ChangeValidator;
use Bundle\News\Validator\Category\CreateValidator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Kernel\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
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
     *
     * @Security(type="IS_ADMIN", message="Vous devez être admin pour accéder à cette page", route="homepage")
     */
    public function createAction(Request $request)
    {
        $errors = [];
        $form = [];
        $createValidator = new CreateValidator($request);

        if ($request->request->has('form') && $createValidator->validate()) {
            $form = $request->request->get('form');

            $category = new NewsCategory();
            $category->setName($form['name']);

            $em = $this->getEntityManager();
            $em->merge($category);
            $em->flush();

            $this->getFlashBag()->add('success','La catégorie a bien été créée');

            return $this->redirectToRoute('homepage');
        } else {
            $errors = $createValidator->getErrors();
        }

        return $this->getTemplate()->renderResponse('@News/category/create.html.twig', [
            'errors' => $errors,
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
     *
     * @Security(type="IS_ADMIN", message="Vous devez être admin pour accéder à cette page", route="homepage")
     */
    public function changeAction(int $id, Request $request)
    {
        $repos = $this->getEntityManager()->getRepository(NewsCategory::class);
        $category = $repos->find($id);

        $form['name'] = $category->getName();
        $form['slug'] = $category->getSlug();

        $errors = [];
        $validator = new ChangeValidator($request);

        if ($request->request->has('form') && $validator->validate()) {
            $form = $request->request->get('form');

            $category
                ->setName($form['name'])
                ->setSlug($form['slug']);

            $em = $this->getEntityManager();
            $em->persist($category);
            $em->flush();

            $this->getFlashBag()->add('success','La catégorie a bien été modifiée');

            return $this->redirectToRoute('homepage');
        } else {
            $errors = $validator->getErrors();
        }

        return $this->getTemplate()->renderResponse('@News/category/change.html.twig', [
            'errors' => $errors,
            'form' => $form,
            'id' => $id,
        ]);
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws ORMException
     * @throws \Exception
     *
     * @Security(type="IS_ADMIN", message="Vous devez être admin pour accéder à cette page", route="homepage")
     */
    public function deleteAction(int $id)
    {
        $repos = $this->getEntityManager()->getRepository(NewsCategory::class);
        $category = $repos->find($id);

        $em = $this->getEntityManager();
        $em->remove($category);
        try {
            $em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
            $this->getFlashBag()->add('danger', 'Impossible de supprimer la catégorie');
        }

        $this->getFlashBag()->add('success', 'La catégorie a bien été supprimée');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     *
     * @Security(type="IS_ADMIN", message="Vous devez être admin pour accéder à cette page", route="homepage")
     */
    public function listingAction()
    {
        $repos = $this->getEntityManager()->getRepository(NewsCategory::class);

        return $this->getTemplate()->renderResponse('@News/category/listing.html.twig', [
            'categories' => $repos->findBy([], ['id' => 'ASC']),
        ]);
    }
}
