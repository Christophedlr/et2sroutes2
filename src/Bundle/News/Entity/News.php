<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\News\Entity;

use Bundle\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class News
 * @package Bundle\News\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="news")
 * @ORM\HasLifecycleCallbacks
 */
class News
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var NewsCategory
     * @ORM\ManyToOne(targetEntity="Bundle\News\Entity\NewsCategory")
     */
    private $category;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Bundle\User\Entity\User")
     */
    private $author;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $text;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdDate;

    private $changedSlug = false;

    public function __construct()
    {
        $this->setCreatedDate(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return News
     */
    public function setId(int $id): News
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return NewsCategory
     */
    public function getCategory(): NewsCategory
    {
        return $this->category;
    }

    /**
     * @param NewsCategory $category
     * @return News
     */
    public function setCategory(NewsCategory $category): News
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return News
     */
    public function setAuthor(User $author): News
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return News
     */
    public function setName(string $name): News
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return News
     */
    public function setText(string $text): News
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return News
     */
    public function setSlug(string $slug): News
    {
        $this->slug = $slug;
        $this->changedSlug = true;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     * @return News
     */
    public function setCreatedDate(\DateTime $createdDate): News
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateSlug()
    {
        if (!$this->changedSlug) {
            $newTitle = preg_replace('#[^a-zA-z0-9]+#', '-', $this->getName());

            if (substr($newTitle, -1) === '-') {
                $newTitle = substr($newTitle, 0, -1);
            }

            $newTitle = strtolower($newTitle);

            $this->setSlug($newTitle);
        }
    }
}
