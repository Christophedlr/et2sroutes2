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
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('user_register_confirmation')
        ->setRoute('/user/register/validation/[a:code]')
        ->setController('Bundle\\User:UserController:validationAction')
        ->addMethod('GET')
    ;
} catch (Exception $e) {
    return $e->getMessage();
}

return $routes;
