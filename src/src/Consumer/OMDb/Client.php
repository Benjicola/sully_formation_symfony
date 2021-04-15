<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Consumer\OMDb;

use App\Consumer\MovieInformationProviderInterface;
use BadMethodCallException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * A client class consuming http://www.omdbapi.com/ API.
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
final class Client implements MovieInformationProviderInterface
{
    /** @var string Api key for OMDb api. */
    private $token;

    /** @var string OMDb host. */
    private $host;

    /** @var array Strict parameters for request on OMDb api. */
    private const REQUEST_BY_PARAMS = [
        'id' => 'i',
        'title' => 't',
        'search' => 's',
    ];

    /** @var array Optional parameters to complete a request. */
    private const OPTIONAL_PARAMS = [
        'type',
        'y',
        'plot',
        'r',
        'page',
        'callback',
        'v',
    ];

    /** @var HttpClientInterface */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient, string $omdbToken, string $omdbHost, LoggerInterface $logger)
    {
        $this->token = $omdbToken;
        $this->host = $omdbHost;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __call(string $method, array $arguments): array
    {
        // Remove 'requestBy' in $method and get the user request parameter (id, title or search)
        $userRequestParameter = strtolower(substr($method, 9));

        // Check the user request parameter
        if (!\array_key_exists($userRequestParameter, self::REQUEST_BY_PARAMS)) {
            throw new BadMethodCallException('Invalid method name. Search by: id, title or search parameters.');
        }

        // The user request parameter has no value
        if (!\array_key_exists(0, $arguments)) {
            throw new InvalidArgumentException('Invalid arguments. No id or title were passed to the request.');
        }

        return $this->requestBy(
            $this->validQueryParameters(self::REQUEST_BY_PARAMS[$userRequestParameter], $arguments)
        );
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function requestBy(array $queryParameters): array
    {
        // Making the request with HTTPClient
        $response = $this->httpClient
            ->request('GET', $this->host, ['query' => $queryParameters])
            ->toArray()
        ;

        // Normally, you should throw a ClientException with the response object
        // But the OMDd Api does not return an error code when you do a bad request
        // It returns an array with ['Response' => false, 'Error' => '...']
        if (\in_array('False', $response, true)) {
            throw new TransportException($response['Response'].' : '.$response['Error']);
        }

        return $response;
    }

    private function validQueryParameters(string $requestParameter, array $arguments): array
    {
        // If no optional parameters were passed, returns an api token and a required query string parameter
        if (!\array_key_exists(1, $arguments)) {
            return ['apikey' => $this->token, $requestParameter => $arguments[0]];
        }

        // Check if all optional parameters are valid
        foreach ($arguments[1] as $key => $value) {
            if (!\in_array($key, self::OPTIONAL_PARAMS, true)) {
                throw new InvalidArgumentException('Invalid query string parameters were passed to the request.');
            }
        }

        // Return a complete 'query' array for HTTPClient
        // @see Symfony\Component\HttpClient\HttpClientTrait
        return array_merge(['apikey' => $this->token, $requestParameter => $arguments[0]], $arguments[1]);
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
