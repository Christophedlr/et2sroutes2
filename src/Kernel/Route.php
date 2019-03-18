<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel;


/**
 * Route definition
 *
 * @author Christophe Daloz - De Los Rios
 * @version 1.0
 * @package Kernel
 */
class Route
{
    /**
     * @var string
     */
    private $methods;
    /**
     * @var string
     */
    private $route;
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getMethods(): string
    {
        return $this->methods;
    }

    /**
     * @param string $method
     * @return Route
     * @throws \Exception
     */
    public function addMethod(string $method)
    {
        if ($method !== 'GET' && $method !== 'POST' && $method !== 'PATCH' && $method !== 'PUT' && $method !== 'DELETE') {
            throw new \Exception('Route - Method not allowed');
        }

        if (!empty($this->methods)) {
            $this->methods .= '|';
        }

        $this->methods .= $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Route
     */
    public function setRoute(string $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     * @return Route
     */
    public function setController(string $controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Route
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
