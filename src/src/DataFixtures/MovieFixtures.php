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

namespace App\DataFixtures;

use App\Entity\Movie;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

/**
 * @see     https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
 * @see     GenreFixtures For ManyToMany relations
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
final class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var string public CONST for reference, concat to an int [0-7]. */
    public const MOVIE_REFERENCE = 'movie-';

    /**
     * @throws Exception Datetime Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getFakeMovies() as [$index, $title, $poster, $country, $genres, $released, $director, $plot]) {
            $movie = new Movie();

            $movie->setTitle($title);
            $movie->setPoster($poster);
            $movie->setCountry($country);
            $movie->addGenre(...$genres);
            $movie->setReleased($released);
            $movie->addDirector($director);
            $movie->setDescription($plot);
            $movie->setAwards('N/A');
            $movie->setProduction('N/A');

            $movie->setRated($this->getRandomRatedValues());
            $movie->setRuntime(random_int(0, 180));
            $movie->setGlobalRatingValue(round((mt_rand() / mt_getrandmax() * 10), 2));
            $movie->setPrice(round((mt_rand() / mt_getrandmax() * 10), 2));

            // More setters can be called here

            $manager->persist($movie);
            $this->addReference(self::MOVIE_REFERENCE.$index, $movie);
        }

        $manager->flush();
    }

    /**
     * Get an array of 6 fake movies.
     */
    private function getFakeMovies(): array
    {
        return [
            [
                0, 'Memento', 'memento.jpeg', 'USA', $this->getGenresBy('thriller', 'mystery', 'drama'),
                new DateTimeImmutable('2001-05-25'), 'Christopher Nolan',
                'A man with short-term memory loss tracks a murderer.',
            ],
            [
                1, 'Insomnia', 'insomnia.jpeg', 'USA', $this->getGenresBy('mystery', 'drama', 'thriller'),
                new DateTimeImmutable('2002-05-24'), 'Christopher Nolan',
                'Two LA detectives investigate the murder of a local teen.',
            ],
            [
                2, 'The Dark Knight', 'the-dark-knight.jpeg', 'USA', $this->getGenresBy('action', 'superhero', 'crime'),
                new DateTimeImmutable('2008-07-18'), 'Christopher Nolan',
                'Batman fights injustice and a new menace known as the Joker.',
            ],
            [
                3, 'Inception', 'inception.jpeg', 'USA, UK', $this->getGenresBy('sci-fi', 'thriller', 'adventure'),
                new DateTimeImmutable('2010-07-16'), 'Christopher Nolan',
                'A thief planting an idea into the mind of a C.E.O',
            ],
            [
                4, 'Man Of Steel', 'man-of-steel.jpeg', 'USA', $this->getGenresBy('action', 'superhero', 'adventure'),
                new DateTimeImmutable('2013-06-14'), 'Christopher Nolan',
                'Clark Kent has to reveal himself to the world and fight Zodd.',
            ],
            [
                5, 'Dunkirk', 'dunkirk.jpeg', 'UK, FR, USA', $this->getGenresBy('action', 'drama'),
                new DateTimeImmutable('2017-07-21'), 'Christopher Nolan',
                'Allied soldiers are surrounded by the German Army in WWII.',
            ],
        ];
    }

    private function getGenresBy(...$references): array
    {
        $genres = [];
        foreach ($references as $reference) {
            $genres[] = $this->getReference(GenreFixtures::GENRE_REFERENCE.$reference);
        }

        return $genres;
    }

    private function getRandomRatedValues(): string
    {
        $ratedValues = ['PG-13', 'R', 'G', 'PG'];

        return $ratedValues[array_rand($ratedValues)];
    }

    /**
     * Get dependencies from entity relations.
     */
    public function getDependencies(): array
    {
        return [GenreFixtures::class];
    }
}
