<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;

$injection = require_once 'config/parameters.php';

$container = new ContainerBuilder();

$container->autowire('__defaults');

if (isset($injection['parameter'])) {
    foreach ($injection['parameter'] as $key => $val) {
        $container->setParameter($key, $val);
    }
} elseif (isset($injection['service'])) {
    foreach ($injection['service'] as $id => $array) {
        $def = $container->register($id, $array['class']);

        if (isset($array['arguments'])) {
            $def->setArguments($array['arguments']);
        }
    }
}
