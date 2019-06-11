<?php
/**
 * Copyright (c) $year Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\Base\Entity;


use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact entity
 * @package Bundle\Base\Entity
 * @author Christophe Daloz - De Los Rios
 * @version 0.2.0a
 */
class Contact
{
    /**
     * @var string
     * @Assert\NotBlank(message="L'adresse e-mail est requise")
     * @Assert\Length(max="50", maxMessage="50 caractères maximum")
     * @Assert\Email(message="Vous devez rentrer une adresse e-mail valide")
     */
    private $mail;

    /**
     * @var string
     * @Assert\NotBlank(message="L'objet du message est obligatoire")
     * @Assert\Length(max="100", maxMessage="50 caractères maximum")
     */
    private $object;

    /***
     * @var string
     * @Assert\NotBlank(message="Vous devez rentrer un message")
     */
    private $message;

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return Contact
     */
    public function setMail(string $mail): Contact
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return Contact
     */
    public function setObject(string $object): Contact
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Contact
     */
    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }
}
