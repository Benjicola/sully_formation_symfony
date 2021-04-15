<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\AfterCallMovieApi;
use App\Event\BeforeCallMovieApi;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MovieInformationCacheListener implements EventSubscriberInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    public function __construct(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }

    public function logBeforeCall(BeforeCallMovieApi $event)
    {
        $key = 'movie'.$event->getArgument('id');
        if (!$this->cacheItemPool->hasItem($key)) {
            return;
        }

        $item = $this->cacheItemPool->getItem($key);
        $event->setMovieObject($item->get());
    }

    public function saveInCache(AfterCallMovieApi $event)
    {
        //TODO ajouter en cache
        dump($event);
    }

    public function saveInCache2(AfterCallMovieApi $event)
    {
        //TODO ajouter en cache
        dump($event);
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeCallMovieApi::class => ['logBeforeCall', 1024],
            AfterCallMovieApi::class => [['saveInCache'], ['saveInCache2', 500]],
        ];
    }
}
