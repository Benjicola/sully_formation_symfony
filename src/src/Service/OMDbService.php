<?php


namespace App\Service;


use App\Consumer\OMDb\Client;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OMDbService
 * @package App\Service
 */
class OMDbService
{
    /** @var Client $omdbClient */
    private Client $omdbClient;

    /** @var EntityManagerInterface $entityManager */
    private EntityManagerInterface $entityManager;

    /**
     * OMDbService constructor.
     * @param Client $omdbClient
     * @param MovieRepository $movieRepository
     */
    public function __construct(Client $omdbClient, EntityManagerInterface $entityManager)
    {
        $this->omdbClient = $omdbClient;
        $this->entityManager = $entityManager;
    }

    /**
     * TODO Ceci est plutôt à mettre dans un REPOSITORY
     *
     * @param string $id
     * @return array
     */
    public function getMovieById(string $id): array
    {
        return $this->omdbClient->requestById($id);
    }

    /**
     * TODO Ceci est plutôt à mettre dans un REPOSITORY
     *
     * @param string $title
     * @return array
     */
    public function getMovieByTitle(string $title): array
    {
        return $this->omdbClient->requestByTitle($title);
    }

    /**
     * @param array $info
     * @return Movie
     */
    public function createMovie(array $info): Movie
    {
        $movie = new Movie();
        $movie->setTitle($info['Title']);
        $movie->setCountry($info['Country']);
        $movie->setDescription($info['Plot']);
        $movie->setPoster('Benjamin');
        $movie->setRated($info['imdbRating']);
        $movie->setReleased(new \DateTime($info['Year']));
        $movie->setAwards($info['Awards']);
        $movie->setProduction($info['Production']);

        return $movie;
    }

    /**
     * @param Movie $movie
     */
    public function saveMovie(Movie $movie): void
    {
        $this->entityManager->persist($movie);
        $this->entityManager->flush();
    }
}