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
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 *
 * @see     https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class Movie
{
    // https://www.php.net/manual/fr/language.oop5.traits.php
    use EntityIdTrait;
    use EntityTimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=128)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Url
     */
    private $poster;

    /**
     * Country assert can be used for ISO3166 format.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=128)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     */
    private $rated;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default": 0, "unsigned": true})
     * @Assert\Type(type="integer")
     * @Assert\Range(
     *     min=1,
     *     max=51420,
     *     minMessage="The running time of the shortest film in the world is {{ limit }}.",
     *     maxMessage="The running time of the longest film in the world is {{ limit }}."
     * )
     */
    private $runtime = 0;

    /**
     * @var Genre[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Genre",
     *     inversedBy="movies",
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"name": "ASC"})
     * @Assert\Count(
     *     min="1",
     *     minMessage="At least one Genre has to be chosen.",
     * )
     */
    private $genres;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $released;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=2, scale=1)
     * @Assert\LessThanOrEqual(10)
     */
    private $globalRatingValue = 0.0;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\LessThanOrEqual(30)
     */
    private $price = 0.00;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=128)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $directors = [];

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $writers = [];

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $actors = [];

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $awards;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     */
    private $production;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $ratings = [];

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $trailers = [];

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $photos = [];

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    public function __toString()
    {
        return $this->title.': '.$this->price.'$';
    }

    /**
     * Init basic information.
     *
     * @throws Exception (UnsatisfiedDependencyException, InvalidArgumentException)
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->genres = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public static function fromArrayWithGenres(array $data, array $genres): self
    {
        $movie = new self();
        $movie->title = $data['Title'];
        $movie->poster = $data['Poster'];
        $movie->country = $data['Country'];
        $movie->rated = $data['Rated'];
        $movie->runtime = preg_replace('/\D/', '', $data['Runtime']);
        $movie->genres = $genres;
        $movie->released = new DateTimeImmutable($data['Released']);
        $movie->globalRatingValue = (float) $data['imdbRating'];
        $movie->price = round((mt_rand() / mt_getrandmax() * 10), 2);
        $movie->description = $data['Plot'];
        $movie->directors = explode(', ', $data['Director']);
        $movie->writers = explode(', ', $data['Writer']);
        $movie->actors = explode(', ', $data['Actors']);
        $movie->awards = $data['Awards'];
        $movie->production = $data['Production'] ?? '';
        $movie->ratings = $data['Ratings'];
        $movie->trailers = null;
        $movie->photos = null;
        $movie->isActive = true;

        return $movie;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRated(): ?string
    {
        return $this->rated;
    }

    public function setRated(string $rated): self
    {
        $this->rated = $rated;

        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function getGenreNames(): string
    {
        $genreNames = [];

        foreach ($this->genres as $genre) {
            $genreNames[] = $genre->getName();
        }

        return implode(', ', $genreNames);
    }

    public function addGenre(Genre ...$genres): self
    {
        foreach ($genres as $genre) {
            if (!$this->genres->contains($genre)) {
                $this->genres->add($genre);
            }
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }

    public function getReleased(): ?DateTimeImmutable
    {
        return $this->released;
    }

    public function setReleased(?DateTimeInterface $released): self
    {
        $this->released = $released instanceof DateTime ? DateTimeImmutable::createFromMutable($released) : $released;

        return $this;
    }

    public function getGlobalRatingValue(): ?float
    {
        return (float) $this->globalRatingValue;
    }

    public function setGlobalRatingValue(float $globalRatingValue): self
    {
        $this->globalRatingValue = $globalRatingValue;

        return $this;
    }

    public function getPrice(): ?float
    {
        return (float) $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDirectors(): ?array
    {
        return $this->directors;
    }

    public function setDirectors(array $directors): self
    {
        $this->directors = $directors;

        return $this;
    }

    public function addDirector(string ...$directors): self
    {
        foreach ($directors as $director) {
            if (!\in_array($director, $this->directors, true)) {
                $this->directors[] = $director;
            }
        }

        return $this;
    }

    public function removeDirector(string $director): self
    {
        $key = array_search($director, $this->directors, true);
        if (false !== $key) {
            unset($this->directors[$key]);
        }

        return $this;
    }

    public function getWriters(): ?array
    {
        return $this->writers;
    }

    public function setWriters(array $writers): self
    {
        $this->writers = $writers;

        return $this;
    }

    public function addWriter(string ...$writers): self
    {
        foreach ($writers as $writer) {
            if (!\in_array($writer, $this->writers, true)) {
                $this->writers[] = $writer;
            }
        }

        return $this;
    }

    public function removeWriter(string $writer): self
    {
        $key = array_search($writer, $this->writers, true);
        if (false !== $key) {
            unset($this->writers[$key]);
        }

        return $this;
    }

    public function getActors(): ?array
    {
        return $this->actors;
    }

    public function setActors(array $actors): self
    {
        $this->actors = $actors;

        return $this;
    }

    public function addActor(string ...$actors): self
    {
        foreach ($actors as $actor) {
            if (!\in_array($actor, $this->actors, true)) {
                $this->actors[] = $actor;
            }
        }

        return $this;
    }

    public function removeActor(string $actor): self
    {
        $key = array_search($actor, $this->actors, true);
        if (false !== $key) {
            unset($this->actors[$key]);
        }

        return $this;
    }

    public function getAwards(): ?string
    {
        return $this->awards;
    }

    public function setAwards(string $awards): self
    {
        $this->awards = $awards;

        return $this;
    }

    public function getProduction(): ?string
    {
        return $this->production;
    }

    public function setProduction(string $production): self
    {
        $this->production = $production;

        return $this;
    }

    public function getRatings(): ?array
    {
        return $this->ratings;
    }

    public function setRatings(array $ratings): self
    {
        $this->ratings = $ratings;

        return $this;
    }

    public function addRating(string ...$ratings): self
    {
        foreach ($ratings as $rating) {
            if (!\in_array($rating, $this->ratings, true)) {
                $this->ratings[] = $rating;
            }
        }

        return $this;
    }

    public function removeRating(string $rating): self
    {
        $key = array_search($rating, $this->ratings, true);
        if (false !== $key) {
            unset($this->ratings[$key]);
        }

        return $this;
    }

    public function getTrailers(): ?array
    {
        return $this->trailers;
    }

    public function setTrailers(array $trailers): self
    {
        $this->trailers = $trailers;

        return $this;
    }

    public function addTrailer(string ...$trailers): self
    {
        foreach ($trailers as $trailer) {
            if (!\in_array($trailer, $this->trailers, true)) {
                $this->trailers[] = $trailer;
            }
        }

        return $this;
    }

    public function removeTrailer(string $trailer): self
    {
        $key = array_search($trailer, $this->trailers, true);
        if (false !== $key) {
            unset($this->trailers[$key]);
        }

        return $this;
    }

    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    public function setPhotos(array $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function addPhoto(string ...$photos): self
    {
        foreach ($photos as $photo) {
            if (!\in_array($photo, $this->photos, true)) {
                $this->photos[] = $photo;
            }
        }

        return $this;
    }

    public function removePhoto(string $trailer): self
    {
        $key = array_search($trailer, $this->trailers, true);
        if (false !== $key) {
            unset($this->trailers[$key]);
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
