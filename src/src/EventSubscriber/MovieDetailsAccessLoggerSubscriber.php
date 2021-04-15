<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\OnMovieDetailsAccess;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MovieDetailsAccessLoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $movieaccessLogger)
    {
        $this->logger = $movieaccessLogger;
    }

    public function logMovieAccess(OnMovieDetailsAccess $event)
    {
        $this->logger->error('Movie Access', ['movie_id' => $event->getSubject()->getId()]);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            OnMovieDetailsAccess::class => 'logMovieAccess',
        ];
    }
}
