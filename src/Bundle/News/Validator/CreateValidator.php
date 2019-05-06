<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\News\Validator;


use Kernel\Form\Validation\AbstractValidator;

class CreateValidator extends AbstractValidator
{
    public function validate()
    {
        if (empty($this->form)) {
            return false;
        }

        if (!$this->isString($this->form['name'])) {
            $this->errors['name'][] = 'Le titre doit être une chaîne de caractères';
        }

        if (!$this->isString($this->form['text'])) {
            $this->errors['text'] = "Un contenu est obligatoire et doit être une chaîne de caractères";
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }
}
