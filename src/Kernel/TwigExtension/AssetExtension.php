<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\TwigExtension;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('asset', [$this, 'assetPath'])
        ];
    }

    public function assetPath(string $value)
    {
        return $this->container->getParameter('vars.uri').
            $this->container->get('router')->generate('homepage').'web/'.$value;
    }
}
