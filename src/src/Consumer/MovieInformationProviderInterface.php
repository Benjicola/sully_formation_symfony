<?php

declare(strict_types=1);

namespace App\Consumer;

interface MovieInformationProviderInterface
{
    public function requestById(string $imdbId, array $parameters = null);

    public function requestByTitle(string $mediaTitle, array $parameters = null);

    public function requestBySearch(string $mediaTitle, array $parameters = null);
}
