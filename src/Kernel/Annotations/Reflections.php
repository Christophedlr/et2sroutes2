<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 27/05/2019
 * Time: 08:09
 */

namespace Kernel\Annotations;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Abstract Reflections class
 * @package Kernel\Annotations
 *
 * @author Christophe Daloz - De Los Rios
 * @version 0.2.0-Alpha
 */
abstract class Reflections
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var string
     */
    protected $defaultMessage;

    /**
     * Add flashbag and redirect to selected route
     *
     * @param string|null $message
     * @param string $route
     * @return RedirectResponse
     * @throws \Exception
     */
    protected function messageAndRedirect($message, string $route)
    {
        $msg = $this->defaultMessage;

        if (!empty($message)) {
            $msg = $message;
        }

        $this->container->get('session')->getFlashBag()->add('danger', $msg);
        return new RedirectResponse($this->container->get('router')->generate($route, []));
    }
}
