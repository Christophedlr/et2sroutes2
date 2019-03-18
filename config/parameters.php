<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

$injection['parameter'] = [];

$injection['parameter'] = array_merge($injection['parameter'], require_once 'parameters/database.php');

return $injection;
