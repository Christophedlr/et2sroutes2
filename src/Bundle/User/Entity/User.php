<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package Bundle\User\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=13, nullable=true, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $registerDate;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $lastConnection;

    public function __construct()
    {
        $this
            ->setLogin('')
            ->setPassword('')
            ->setMail('')
            ->setPlainPassword('')
            ->setRegisterDate(new \DateTime())
            ->setLastConnection(new \DateTime())
            ->setActive(false);
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return User
     */
    public function setMail(string $mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisterDate(): \DateTime
    {
        return $this->registerDate;
    }

    /**
     * @param \DateTime $registerDate
     * @return User
     */
    public function setRegisterDate(\DateTime $registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastConnection(): \DateTime
    {
        return $this->lastConnection;
    }

    /**
     * @param \DateTime $lastConnection
     * @return User
     */
    public function setLastConnection(\DateTime $lastConnection)
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatePassword()
    {
        if (!empty($this->getPlainPassword())) {
            $this->setPassword(password_hash($this->getPlainPassword(), PASSWORD_BCRYPT));
            $this->setPlainPassword('');
        }
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return User
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }
}
