<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\Base\Controller;


use Kernel\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->getTemplate()->renderResponse('@Base/index.html.twig');
    }
}
