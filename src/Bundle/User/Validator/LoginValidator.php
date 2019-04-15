<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Validator;


use Kernel\Form\Validation\AbstractValidator;

class LoginValidator extends AbstractValidator
{
    public function validate()
    {
        if (empty($this->form)) {
            return false;
        }

        if (!$this->isString($this->form['login'])) {
            $this->errors['login'][] = 'Le login doit être une chaîne de caractères';
        }

        if (!$this->isValidWithRegexp(
            $this->form['password'], self::$REGEXP_PASSWORD)) {
            $this->errors['password'][] = 'Le mot de passe doit faire 8 caractères minimum '
            .'composés de letttres (majuscules et minuscules) et des chiffres';
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }
}
