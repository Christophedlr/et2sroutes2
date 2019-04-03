<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

$injection['parameter'] = [];
$injection['service'] = [];

$injection['parameter'] = array_merge($injection['parameter'], require_once 'parameters/database.php');
$injection['parameter'] = array_merge($injection['parameter'], require_once 'parameters/vars.php');
$injection['service'] = array_merge($injection['service'], require_once 'parameters/objects.php');

return $injection;
