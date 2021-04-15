<?php

declare(strict_types=1);

namespace App\Menu;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;

final class MainMenuProvider
{
    /**
     * @var GenreRepository
     */
    private $genreRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        GenreRepository $genreRepository,
        UrlGeneratorInterface $urlGenerator,
        CacheInterface $cache
    ) {
        $this->genreRepository = $genreRepository;
        $this->urlGenerator = $urlGenerator;
        $this->cache = $cache;
    }

    public function generateMenu(): array
    {
        return $this->cache->get('main_menu', [$this, 'generateMenuFromDb']);
    }

    private function generateMenuFromDb(): array
    {
        $genres = $this->genreRepository->findAll();

        return array_map(function (Genre $genre) {
            return [
                'title' => $genre->getName(),
                'url' => $this->urlGenerator->generate('app_admin_genre_index', ['id' => $genre->getUuid()]),
            ];
        }, $genres);
    }
}
