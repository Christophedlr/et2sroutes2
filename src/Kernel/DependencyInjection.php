<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$injection = require_once 'config/parameters.php';

$container = new ContainerBuilder();

$container->autowire('__defaults');

if (isset($injection['parameter'])) {
    foreach ($injection['parameter'] as $key => $val) {
        $container->setParameter($key, $val);
    }
}

if (isset($injection['service'])) {
    foreach ($injection['service'] as $id => $array) {
        $def = $container->register($id, $array['class']);

        if (isset($array['arguments'])) {
            $i = 0;

            foreach ($array['arguments'] as $argument) {
                if (!is_array($argument)) {
                    if (substr($argument, 0, 1) === '@') {
                        $def->setArgument($i, new Reference(substr($argument, 1)));
                    } else {
                        $def->setArgument($i, $argument);
                    }
                }

                $i++;
            }
        }
    }
}
