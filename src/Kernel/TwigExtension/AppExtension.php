<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\TwigExtension;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'path']),
            new TwigFunction('pathab', [$this, 'absolute']),
        ];
    }

    /**
     * Return path of route
     *
     * @param string $route
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function path(string $route, array $params = [])
    {
        return $this->container->get('router')->generate($route, $params);
    }

    /**
     * Return an absolute path of route
     *
     * @param string $route
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function absolute(string $route, array $params = [])
    {
        return $this->container->getParameter('vars.uri').
            $this->path($route, $params);
    }
}
