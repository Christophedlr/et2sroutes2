<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel;


use Kernel\TwigExtension\AssetExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Environment;

/**
 * Base controller
 *
 * @author Christophe Daloz - De Los Rios
 * @version 1.0
 * @package Kernel
 */
class Controller
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;

        $explode = explode('\\', get_called_class());

        for ($i=0; $i < count($explode); $i++) {
            if ($explode[$i] === 'Controller') {
                $this->container->get('twig.loader')->addPath(
                    sprintf('src/Bundle/%s/web/template', $explode[$i-1]),
                    $explode[$i-1]
                );
                break;
            }
        }

        $this->container->get('twig.environment')->addExtension(new AssetExtension($this->container));
    }

    /**
     * @return Renderer
     * @throws \Exception
     */
    public function getTemplate()
    {
        return new Renderer($this->container->get('twig.environment'));
    }
}
