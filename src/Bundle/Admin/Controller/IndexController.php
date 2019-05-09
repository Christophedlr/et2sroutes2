<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\Admin\Controller;


use Bundle\Admin\Entity\Bundle;
use Kernel\Controller;

/**
 * Class IndexController
 * @package Bundle\Admin\Controller
 */
class IndexController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction()
    {
        if (is_null($this->getSession()->get('user')->getId())) {
            $this->getFlashBag()->add('danger', 'Vous devez être identifié pour accéder à cette page');
            return $this->redirectToRoute('homepage');
        }

        $repos = $this->getEntityManager()->getRepository(Bundle::class);

        return $this->getTemplate()->renderResponse('@Admin/index.html.twig', [
            'bundles' => $repos->findAll()
        ]);
    }
}
