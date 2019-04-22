<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 22/04/2019
 * Time: 10:08
 */

namespace Bundle\User\Validator;


use Kernel\Form\Validation\AbstractValidator;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfilePasswordValidator extends AbstractValidator
{
    public function validate()
    {
        if (empty($this->form)) {
            return false;
        }

        /** @var Session $session */
        $session = $this->request->attributes->get('container')->get('session');

        if (!$this->isValidWithRegexp(
            $this->form['old_password'], self::$REGEXP_PASSWORD)) {
            $this->errors['old_password'][] = 'Le mot de passe doit faire 8 caractères minimum '
                .'composés de letttres (majuscules et minuscules) et des chiffres';
        }

        if (!$this->isValidWithRegexp(
            $this->form['password'], self::$REGEXP_PASSWORD)) {
            $this->errors['password'][] = 'Le mot de passe doit faire 8 caractères minimum '
                .'composés de letttres (majuscules et minuscules) et des chiffres';
        }

        if (!$this->isValidWithRegexp(
            $this->form['password_confirm'], self::$REGEXP_PASSWORD)) {
            $this->errors['password_confirm'][] = 'Le mot de passe doit faire 8 caractères minimum '
                .'composés de letttres (majuscules et minuscules) et des chiffres';
        }

        if (!password_verify($this->form['old_password'], $session->get('user')->getPassword())) {
            $this->errors['old_password'][] = "L'ancien mot de passe ne correspond pas";
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }
}
