<?php

declare(strict_types=1);

namespace App\Consumer\OMDb;

use App\Consumer\MovieInformationProviderInterface;
use Psr\Log\LoggerInterface;

/**
 * A client class consuming http://www.omdbapi.com/ API.
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
final class TracableClient implements MovieInformationProviderInterface
{
    /**
     * @var MovieInformationProviderInterface
     */
    private $client;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MovieInformationProviderInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function __call($name, $arguments)
    {
        $this->logger->debug('call', ['method' => $name]);
        $result = $this->client->__call($name, $arguments);
        $this->logger->debug('ennd call', ['methode' => $name, 'result' => $result]);

        return $result;
    }

    public function requestById(string $imdbId, array $parameters = null)
    {
        return $this->__call('requestById', \func_get_args());
    }

    public function requestByTitle(string $mediaTitle, array $parameters = null)
    {
        return $this->__call('requestByTitle', \func_get_args());
    }

    public function requestBySearch(string $mediaTitle, array $parameters = null)
    {
        return $this->__call('requestBySearch', \func_get_args());
    }
}
