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
    $routes[] = (new Route())
        ->setName('news_admin_delete')
        ->setRoute('/admin/news/delete/[i:id]')
        ->setController('Bundle\\News:NewsController:deleteAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('news_cat_admin_add')
        ->setRoute('/admin/news/category/add')
        ->setController('Bundle\\News:CategoryController:createAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('news_cat_admin_change')
        ->setRoute('/admin/news/category/change/[i:id]')
        ->setController('Bundle\\News:CategoryController:changeAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('news_cat_admin_delete')
        ->setRoute('/admin/news/category/delete/[i:id]')
        ->setController('Bundle\\News:CategoryController:deleteAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('news_admin_list')
        ->setRoute('/admin/news')
        ->setController('Bundle\\News:NewsController:listingAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('news_cat_admin_list')
        ->setRoute('/admin/news/category')
        ->setController('Bundle\\News:CategoryController:listingAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('dashboard_admin')
        ->setRoute('/admin')
        ->setController('Bundle\\Admin:IndexController:indexAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;

    $routes[] = (new Route())
        ->setName('contact')
        ->setRoute('/contact')
        ->setController('Bundle\\Base:ContactController:contactAction')
        ->addMethod('GET')
        ->addMethod('POST')
    ;
} catch (Exception $e) {
    return $e->getMessage();
}

return $routes;
