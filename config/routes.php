<?php

use Kernel\Route;

try {
    $routes[] = (new Route())
        ->setName('homepage')
        ->setRoute('/')
        ->setController('Bundle\\Base:IndexController:indexAction')
        ->addMethod('GET')
    ;
} catch (Exception $e) {
    return $e->getMessage();
}

return $routes;
