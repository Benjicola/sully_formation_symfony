<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Movie;
use Symfony\Component\EventDispatcher\GenericEvent;

final class BeforeCallMovieApi extends GenericEvent
{
    public function setMovieObject(Movie $movie)
    {
        $this->stopPropagation();
        $this->setArgument('cached_object', $movie);
    }
}
