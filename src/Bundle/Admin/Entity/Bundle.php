<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Bundle\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Bundle
 * @package Bundle\Admin\Entity
 * @author Christophe Daloz - De Los Rios
 * @version 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="bundle")
 */
class Bundle
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
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $octicon;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $version;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Bundle
     */
    public function setId(int $id): Bundle
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
     * @return Bundle
     */
    public function setName(string $name): Bundle
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getOcticon(): string
    {
        return $this->octicon;
    }

    /**
     * @param string $octicon
     * @return Bundle
     */
    public function setOcticon(string $octicon): Bundle
    {
        $this->octicon = $octicon;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return Bundle
     */
    public function setVersion(string $version): Bundle
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Bundle
     */
    public function setDescription(string $description): Bundle
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Bundle
     */
    public function setPath(string $path): Bundle
    {
        $this->path = $path;
        return $this;
    }
}
