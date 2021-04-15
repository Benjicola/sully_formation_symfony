<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\BeforeCallMovieApi;
use Psr\Log\LoggerInterface;

final class MovieInformationListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logBeforeCall(BeforeCallMovieApi $event)
    {
        if ('before_fetch' === $event->getSubject()) {
            $this->logger->info('call '.__METHOD__);
        }
    }
}
