<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityTimestampableTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 *
 * @see     https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class Genre
{
    // https://www.php.net/manual/fr/language.oop5.traits.php
    use EntityIdTrait;
    use EntityTimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=64)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Url
     */
    private $poster;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="genres")
     * @ORM\OrderBy({"title": "ASC"})
     */
    private $movies;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Init basic information.
     *
     * @throws Exception (UnsatisfiedDependencyException, InvalidArgumentException)
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->movies = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->addGenre($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
            $movie->removeGenre($this);
        }

        return $this;
    }
}
