<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\Admin\Controller;


use Bundle\Admin\Entity\Bundle;
use Kernel\Annotations\Reflections\Security;
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
     * @Security(type="IS_ADMIN", message="Vous devez être admin pour accéder à cette page", route="homepage")
     */
    public function indexAction()
    {
        $repos = $this->getEntityManager()->getRepository(Bundle::class);

        return $this->getTemplate()->renderResponse('@Admin/index.html.twig', [
            'bundles' => $repos->findAll()
        ]);
    }
}
