<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Controller;


use Kernel\Controller;

class UserController extends Controller
{
    public function registerAction()
    {
        return $this->getTemplate()->renderResponse('@User/register.html.twig');
    }
}
