<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Validator;


use Kernel\Form\Validation\AbstractValidator;

class LostValidator extends AbstractValidator
{
    public function validate()
    {
        if (empty($this->form)) {
            return false;
        }

        if (!$this->isString($this->form['login'])) {
            $this->errors['login'][] = 'Le login doit être une chaîne de caractères';
        }

        if (!$this->isEmail($this->form['mail'])) {
            $this->errors['mail'] = "L'adresse e-mail n'est pas valide";
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }
}
