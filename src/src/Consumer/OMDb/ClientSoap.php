<?php

declare(strict_types=1);

namespace App\Consumer\OMDb;

use App\Consumer\MovieInformationProviderInterface;

final class ClientSoap implements MovieInformationProviderInterface
{
    public function requestById(string $imdbId, array $parameters = null)
    {
        // TODO: Implement requestById() method.
    }

    public function requestByTitle(string $mediaTitle, array $parameters = null)
    {
        // TODO: Implement requestByTitle() method.
    }

    public function requestBySearch(string $mediaTitle, array $parameters = null)
    {
        // TODO: Implement requestBySearch() method.
    }
}
