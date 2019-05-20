<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\Annotations\Reflections;

use Doctrine\Common\Annotations\Annotation;


/**
 * @Annotation
 * @Annotation\Target("METHOD")
 *
 */
final class Security
{
    /**
     * @var string
     * @Annotation\Enum({"IS_ADMIN", "IS_NOT_ADMIN", "IS_ANONYMOUS", "IS_USER"})
     * @Annotation\Required
     */
    public $type;

    /**
     * @var string
     */
    public $message;

    /**
     * @var  string
     * @Annotation\Required
     */
    public $route;
}
