<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;

// replace with file to your own project bootstrap
require_once 'vendor/autoload.php';

$dbParams = require_once 'config/parameters/database.php';

$database['host'] = $dbParams['database.host'];
$database['user'] = $dbParams['database.user'];
$database['password'] = $dbParams['database.password'];
$database['driver'] = $dbParams['database.driver'];
$database['port'] = $dbParams['database.port'];
$database['dbname'] = $dbParams['database.name'];

require_once "vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/Annotation.php";

$scan = scandir(
    'vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/Annotation'
);

foreach ($scan as $index => $value) {
    if (substr($value, strrpos($value, '.')) === '.php') {
        var_dump($value);
        require_once "vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/Annotation/$value";
    }
}

$scan = scandir(
    'vendor/symfony/validator/constraints'
);

foreach ($scan as $index => $value) {
    $pos = strrpos($value, '.')-9;

    if (substr($value, $pos) === 'Validator.php') {
        $name = 'vendor/symfony/validator/constraints/'.substr($value, 0, $pos).'.php';
        require_once $name;
    }
}


$setup = Setup::createAnnotationMetadataConfiguration(['src']);

$em = EntityManager::create($database, $setup);
$em->getConfiguration()->setMetadataDriverImpl(AnnotationDriver::create(['src']));

return ConsoleRunner::createHelperSet($em);
