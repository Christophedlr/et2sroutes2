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

    $routes[] = (new Route())
        ->setName('user_login')
        ->setRoute('/user/login')
        ->setController('Bundle\\User:UserController:loginAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('user_disconnect')
        ->setRoute('/user/disconnect')
        ->setController('Bundle\\User:UserController:disconnectAction')
        ->addMethod('GET')
    ;

    $routes[] = (new Route())
        ->setName('user_password_lost')
        ->setRoute('/user/lost')
        ->setController('Bundle\\User:UserController:lostAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('user_profile')
        ->setRoute('/user/profile')
        ->setController('Bundle\\User:UserController:profileAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('news_admin_add')
        ->setRoute('/admin/news/add')
        ->setController('Bundle\\News:NewsController:createAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('news_admin_change')
        ->setRoute('/admin/news/change/[i:id]')
        ->setController('Bundle\\News:NewsController:changeAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;
} catch (Exception $e) {
    return $e->getMessage();
}

return $routes;
