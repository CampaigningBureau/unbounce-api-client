<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 28.02.18
 * Time: 11:44
 */

namespace CampaigningBureau\UnbounceApiClient;

use CampaigningBureau\UnbounceApiClient\Authorization\AuthorizationDriver;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class UnbounceApiRequest
{

    private static $unbounceAPIBaseUrl = 'https://api.unbounce.com';

    /** @var \GuzzleHttp\Psr7\Request $request the wrapped Guzzle Request */
    private $request;

    /** @var string $method the method the request uses */
    private $method;
    /** @var string $uri the uri the request uses */
    private $uri;

    /** @var Client $client the guzzle client to use for sending */
    private $client;

    /** @var AuthorizationDriver $authenticationDriver for Unbounce communication */
    private $authenticationDriver;

    /** @var array $requestOptions the options that are used for this request */
    private $requestOptions;

    private $apiVersion = "v0.4";

    /**
     * UnbounceApiRequest constructor.
     *
     * @param string              $method
     * @param string              $path the path for the resource
     * @param Client              $client
     * @param AuthorizationDriver $authenticationDriver
     */
    public function __construct(string $method, string $path, Client $client, AuthorizationDriver $authenticationDriver)
    {
        $this->authenticationDriver = $authenticationDriver;
        $this->client = $client;
        $this->method = $method;

        $this->uri = UnbounceApiRequest::$unbounceAPIBaseUrl . $path;

        $this->request = $this->authenticationDriver->prepareRequest(new Request($this->method, $this->uri));

        $this->requestOptions = [];
        // Use V4 of Api
        $this->requestOptions["headers"] = ["Accept" => "application/vnd.unbounce.api." . $this->apiVersion . "+json"];
    }

    /**
     * @param int $limit
     *
     * @return UnbounceApiRequest
     */
    public function withLimit(int $limit): UnbounceApiRequest
    {
        $limit = max(min(1000, $limit), 1);
        if (!key_exists("query", $this->requestOptions))
        {
            $this->requestOptions["query"] = [];
        }
        $this->requestOptions["query"]["limit"] = (string)$limit;

        return $this;
    }

    /**
     * @return Response
     * @throws UnbounceApiException
     */
    public function send(): ResponseInterface
    {
        try
        {
            $response = $this->client->send($this->request, $this->requestOptions);

            return $response;
        } catch (BadResponseException $badResponseException)
        {
            $this->runErrorHandling($badResponseException->getResponse());
        }
    }


    /**
     * @param ResponseInterface $response
     *
     * @throws UnbounceApiException
     */
    private function runErrorHandling(ResponseInterface $response)
    {
        switch ($response->getStatusCode())
        {
            case 400:
                throw new UnbounceApiException(
                    "The request could not be understood, possible syntax malformation.", $response->getStatusCode()
                );
            case 401:
                throw new UnauthorizedApiException(
                    "The request requires user authentication. API Key or Access Token is missing.", $response->getStatusCode()
                );
            case 403:
                throw new UnbounceApiException(
                    "The API Key is forbidden to access the resource, or the Access Token is bad or has expired.", $response->getStatusCode()
                );
            case 404:
                throw new UnbounceApiException("The server has not found anything matching the request-uri.", $response->getStatusCode());
            case 409:
                throw new UnbounceApiException(
                    "The request could not be completed due to a conflict with the current state of the resource.", $response->getStatusCode()
                );
            case 429:
                throw new UnbounceApiException(
                    "Too many request in a given amount of time.", $response->getStatusCode()
                );
            default:
                throw new UnbounceApiException(
                    "Something went wrong on Unbounce's end.", $response->getStatusCode()
                );
        }
    }
}