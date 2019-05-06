<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\News\Controller;


use Bundle\News\Entity\News;
use Kernel\Controller;

class NewsController extends Controller
{
    public function returnNewsAction()
    {
        $repos = $this->getEntityManager()->getRepository(News::class);

        return $this->getTemplate()->getRenderer()->render('@News/return_news.html.twig', [
            'news' =>$repos->findAll(),
        ]);
    }
}
