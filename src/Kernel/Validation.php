<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 10/06/2019
 * Time: 06:55
 */

namespace Kernel;


use Symfony\Component\Validator\Validation as SymfonyValidation;
use Symfony\Component\Validator\ValidatorBuilder;

class Validation
{
    /**
     * @var ValidatorBuilder
     */
    private $validator;

    public function __construct()
    {
        $this->validator = SymfonyValidation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }

    public function validate($value, $constraints = null, $groups = null)
    {
        $errors = $this->validator->validate($value, $constraints, $groups);
        $messages = [];

        foreach ($errors as $error) {
            $messages[$error->getPropertyPath()][] = $error->getMessage();
        }

        return $messages;
    }
}
