<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Entity\Movie;
use App\Event\AfterCallMovieApi;
use App\Event\BeforeCallMovieApi;
use App\Repository\GenreRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class MovieInformationProvider
{
    /**
     * @var GenreRepository
     */
    private $genreRepository;
    /**
     * @var MovieInformationProviderInterface
     */
    private $client;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        GenreRepository $genreRepository,
        MovieInformationProviderInterface $client,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->genreRepository = $genreRepository;
        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;
    }

    /*
    * Fetch movie data by id with OMDb client and return a Movie object and its genres.
    */
    public function findFromApi(string $id): Movie
    {
        $this->eventDispatcher->dispatch($event = new BeforeCallMovieApi(null, \func_get_args()));

        if ($event->isPropagationStopped() && $event->hasArgument('cached_object')) {
            return $event->getArgument('cached_object');
        }

        $movieData = $this->client->requestById($id);

        $movie = Movie::fromArrayWithGenres(
            $movieData,
            $this->genreRepository->findBy(['name' => explode(', ', $movieData['Genre'])])
        );

        $this->eventDispatcher->dispatch(new AfterCallMovieApi($movie));

        return $movie;
    }

    /*
     * Fetch movie data by title with OMDb client and return a Movie object and its genres.
     */
    public function findOneByTitleFromApi(string $title): Movie
    {
        $movieData = $this->client->requestByTitle($title);

        return Movie::fromArrayWithGenres(
            $movieData,
            $this->genreRepository->findBy(['name' => explode(', ', $movieData['Genre'])])
        );
    }
}
