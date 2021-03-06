<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\News\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class NewsCategory
 * @package Bundle\News\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="news_category")
 * @ORM\HasLifecycleCallbacks
 */
class NewsCategory
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $slug;

    private $changedSlug = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return NewsCategory
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return NewsCategory
     */
    public function setName(string $name)
    {
        $this->name = $name;
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
     * @return NewsCategory
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
        $this->changedSlug = true;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
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
