<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\TwigExtension;


use Kernel\BBCode\BBCodeParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Error\RuntimeError;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

/**
 * Add functions and global vars for application
 *
 * @package Kernel\TwigExtension
 * @author Christophe Daloz - De Los Rios
 * @version 0.1.0a
 */
class AppExtension extends AbstractExtension implements GlobalsInterface
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
            new TwigFunction('controller', [$this, 'controller'], ['is_safe' => ['html']]),
            new TwigFunction('parser', [$this, 'parserBBCodeAndSmileys'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getGlobals()
    {
        return [
            'user' => $this->getUser(),
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

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getUser()
    {
        return $this->container->get('session')->get('user');
    }

    /**
     * Return a content of controller action
     *
     * @param string $controllerRoute
     * @param array $params
     * @return string
     * @throws RuntimeError
     */
    public function controller(string $controllerRoute, array $params = [])
    {
        $explode = explode(':', $controllerRoute);
        $class = $explode[0].'\\Controller\\'.$explode[1];

        if (class_exists($class)) {
            $instance = new $class($this->container);

            if (is_callable([$instance, $explode[2]])) {
                $response = call_user_func_array([$instance, $explode[2]], $params);
            } else {
                throw new RuntimeError('Action '.$explode[2].' of Controller '.$class.' does not exist');
            }
        } else {
            throw new RuntimeError('Controller '.$class.' does not exist');
        }

        return $response;
    }

    public function parserBBCodeAndSmileys(string $text): string
    {
        $newText = nl2br($text);

        $parser = new BBCodeParser();
        $newText = $parser->parser($newText);

        return $newText;
    }
}
