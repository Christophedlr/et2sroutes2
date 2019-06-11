<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\User\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(
     *     min=6,
     *     max=50,
     *     minMessage="Le login doit faire 6 caractères minimum",
     *     maxMessage="Le login ne doit pas faire plus de 50 caractères au maximum"
     )
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",
     *     message="Le mot de passe doit faire 8 caractères minimum,
      composés de letttres (majuscules et minuscules) et des chiffres"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Assert\Email(message="L'adresse e-mail n'est pas valide")
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=13, nullable=true, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50, nullable=true, unique=true)
     */
    private $avatar;

    /**
     * @var string
     * @Assert\Length(max=255, maxMessage="Le lien ne peut dépasser les 255 caractères")
     */
    private $avatarLink;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private $gravatar;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $admin;

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
            ->setAdmin(false)
            ->setRegisterDate(new \DateTime())
            ->setLastConnection(new \DateTime())
            ->setActive(false)
            ->setGravatar(false)
        ;
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
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * @param \DateTime $registerDate
     * @return User
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastConnection()
    {
        return $this->lastConnection;
    }

    /**
     * @param \DateTime $lastConnection
     * @return User
     */
    public function setLastConnection($lastConnection)
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return User
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param bool $admin
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return bool
     */
    public function getGravatar()
    {
        return $this->gravatar;
    }

    /**
     * @param bool $gravatar
     * @return User
     */
    public function setGravatar($gravatar)
    {
        $this->gravatar = $gravatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarLink(): string
    {
        return $this->avatarLink;
    }

    /**
     * @param string $avatarLink
     * @return User
     */
    public function setAvatarLink(string $avatarLink): User
    {
        $this->avatarLink = $avatarLink;
        return $this;
    }
}
