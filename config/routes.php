<?php

use Kernel\Route;

try {
    $routes[] = (new Route())
        ->setName('homepage')
        ->setRoute('/')
        ->setController('Bundle\\Base:IndexController:indexAction')
        ->addMethod('GET')
    ;

    $routes[] = (new Route())
        ->setName('user_register')
        ->setRoute('/user/register')
        ->setController('Bundle\\User:UserController:registerAction')
        ->addMethod('GET')
    ;
} catch (Exception $e) {
    return $e->getMessage();
}

return $routes;
