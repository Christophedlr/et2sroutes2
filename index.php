<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once 'vendor/autoload.php';
require_once 'src/Kernel/Router.php';
require_once  'src/Kernel/DependencyInjection.php';

$request = Request::createFromGlobals();
$container->register('request', $request);
$container->set('router', $router);

$match = $router->match();
$explode = explode(':', $match['target']);
$class = $explode[0].'\\Controller\\'.$explode[1];

if (class_exists($class)) {
    $instance = new $class($container);

    if (is_callable([$instance, $explode[2]])) {
        $match['params'][Request::class] = $request;
        /** @var Response $response */
        $response = call_user_func_array([$instance, $explode[2]], $match['params']);
    }
} else {
    $response = new Response('Error 404', 404);
}

$response->send();
