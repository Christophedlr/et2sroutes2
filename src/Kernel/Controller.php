<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel;


use Symfony\Component\DependencyInjection\ContainerBuilder;

class Controller
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }
}
