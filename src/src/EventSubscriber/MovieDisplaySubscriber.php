<?php

namespace App\EventSubscriber;

use App\Entity\Movie;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovieDisplaySubscriber implements EventSubscriberInterface
{
    /** @var LoggerInterface $logger */
    private LoggerInterface $logger;

    /** @var Movie $movie */
    private Movie $movie;

    /**
     * MovieDisplaySubscriber constructor.
     * @param LoggerInterface $logger
     * @param Movie $movie
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onMovieDisplay(EventSubscriberInterface $eventSubscriber)
    {
        $this->logger->info(
            sprintf('La page sur le film %s vient d être consultée',
            $this->movie->getTitle())
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            'movie.display' => 'onMovieDisplay',
        ];
    }

    /**
     * @param Movie $movie
     * @return $this
     */
    public function setMovie(Movie $movie): MovieDisplaySubscriber
    {
        $this->movie = $movie;

        return $this;
    }
}
