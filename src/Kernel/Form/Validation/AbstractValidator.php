<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\Form\Validation;


use Symfony\Component\HttpFoundation\Request;

abstract class AbstractValidator
{
    protected $form = [];
    protected $errors = [];
    protected $request;

    public static $REGEXP_PASSWORD = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';

    public function __construct(Request $request)
    {
        $this->request = $request;

        if ($request->request->has('form')) {
            $this->form = $request->request->get('form', []);
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isString($value)
    {
        return is_string($value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isDateTime($value)
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $value);

        return $dateTime->format('Y-m-d H:i:s') === $value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @param string $regexp
     * @return bool
     */
    protected function isValidWithRegexp($value, string $regexp)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regexp]]) === false) {
            return false;
        }

        return true;
    }

    public abstract function validate();
}
