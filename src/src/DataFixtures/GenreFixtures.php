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

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

/**
 * @see     https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
 * @see     MovieFixtures For relations
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
final class GenreFixtures extends Fixture implements FixtureGroupInterface
{
    /** @var string public CONST for reference used in MovieFixtures, concat to genre name. */
    public const GENRE_REFERENCE = 'genre-';

    /**
     * Load 15 types of IMDB Genres to DB.
     *
     * @throws Exception Datetime Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getStaticIMDBGenres() as [$index, $poster, $name, $description]) {
            $genre = new Genre();

            $genre->setPoster($poster);
            $genre->setName($name);
            $genre->setDescription($description);

            $manager->persist($genre);
            $this->addReference(self::GENRE_REFERENCE.strtolower($genre->getName()), $genre);
        }

        $manager->flush();
    }

    /**
     * Get an array of IMDB Genres.
     *
     * @see    https://www.imdb.com/feature/genre
     */
    private function getStaticIMDBGenres(): array
    {
        return [
            [0, $this->getPosterUrl('Comedy._CB1513316167'), 'Comedy', 'Comedy genre is very funny !'],
            [1, $this->getPosterUrl('Sci-Fi._CB1513316168'), 'Sci-Fi', 'Sci-Fi based on future and unknown universes.'],
            [2, $this->getPosterUrl('Horror._CB1513316168'), 'Horror', 'Don\'t be scared !'],
            [3, $this->getPosterUrl('Romance._CB1513316168'), 'Romance', 'For those who want love.'],
            [4, $this->getPosterUrl('Action._CB1513316166'), 'Action', 'Rage, guns, war and so on !'],
            [5, $this->getPosterUrl('Thriller._CB1513316169'), 'Thriller', 'Unbearable suspense until the end.'],
            [6, $this->getPosterUrl('Drama._CB1513316168'), 'Drama', null],
            [7, $this->getPosterUrl('Mystery._CB1513316168'), 'Mystery', null],
            [8, $this->getPosterUrl('Crime._CB1513316167'), 'Crime', null],
            [9, $this->getPosterUrl('Animation._CB1513316167'), 'Animation', 'For kids and even adults !'],
            [10, $this->getPosterUrl('Adventure._CB1513316166'), 'Adventure', null],
            [11, $this->getPosterUrl('Fantasy._CB1513316168'), 'Fantasy', ''],
            [12, $this->getPosterUrl('Comedy-Romance._CB1513316167'), 'Comedy-Romance', 'A mix between love and joy !'],
            [13, $this->getPosterUrl('Action-Comedy._CB1513316166'), 'Action-Comedy', null],
            [14, $this->getPosterUrl('Superhero._CB1513316168'), 'Superhero', 'From Superman to Batman via iron Man'],
        ];
    }

    /**
     * Media CDN from amazon to get IMDB Genre images
     */
    private function getPosterUrl(string $resource): string
    {
        return 'https://m.media-amazon.com/images/G/01/IMDb/genres/'.$resource.'_SX233_CR0,0,233,131_AL_.jpg';
    }

    public static function getGroups(): array
    {
        return ['independent'];
    }
}
