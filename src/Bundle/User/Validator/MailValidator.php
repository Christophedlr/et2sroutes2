<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 22/04/2019
 * Time: 11:17
 */

namespace Bundle\User\Validator;


use Kernel\Form\Validation\AbstractValidator;

class MailValidator extends AbstractValidator
{
    public function validate()
    {
        if (empty($this->form)) {
            return false;
        }

        /** @var Session $session */
        $session = $this->request->attributes->get('container')->get('session');

        if (!$this->isEmail($this->form['old_mail'])) {
            $this->errors['old_mail'] = "L'adresse e-mail n'est pas valide";
        }

        if (!$this->isEmail($this->form['mail'])) {
            $this->errors['mail'] = "L'adresse e-mail n'est pas valide";
        }

        if (!$this->isEmail($this->form['mail_confirm'])) {
            $this->errors['mail_confirm'] = "L'adresse e-mail n'est pas valide";
        }

        if ($this->form['mail'] !== $this->form['mail_confirm']) {
            $this->errors['mail'][] = 'Les deux e-mail ne concordent pas';
            $this->errors['mail_confirm'][] = 'Les deux e-mail ne concordent pas';
        }

        if ($this->form['old_mail'] === $session->get('user')->getMail()) {
            $this->errors['old_mail'][] = "L'ancien e-mail ne correspond pas";
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }
}
