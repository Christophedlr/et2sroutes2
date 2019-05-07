<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\News\Validator\Category;


use Kernel\Form\Validation\AbstractValidator;

class ChangeValidator extends AbstractValidator
{
    public function validate()
    {
        if (empty($this->form)) {
            return false;
        }

        if (!$this->isString($this->form['name'])) {
            $this->errors['name'][] = 'Le titre doit être une chaîne de caractères';
        }

        if (!$this->isString($this->form['slug'])) {
            $this->errors['slug'][] = 'Le slug doit être une chaîne de caractères';
        }

        if (!$this->isValidWithRegexp($this->form['slug'], '#^[a-z0-9\-]+$#')) {
            $this->errors['slug'][] = 'Le slug ne doit pas avoir autre chose que des minuscules, chiffres et tirets';
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }
}
