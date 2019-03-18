<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

$routes = require_once 'config/routes.php';

$router = new AltoRouter();

/** @var \Kernel\Route $route */
foreach ($routes as $route) {
    try {
        $router->map($route->getMethods(), $route->getRoute(), $route->getController(), $route->getName());
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
