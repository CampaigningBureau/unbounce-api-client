<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 25.02.18
 * Time: 00:32
 */

namespace CampaigningBureau\UnbounceApiClient;

use CampaigningBureau\UnbounceApiClient\Authorization\AuthorizationDriver;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class UnbounceApiClient
{

    /**
     * @var GuzzleClient $guzzleClient
     */
    private $guzzleClient;

    /**
     * @var AuthorizationDriver $authorizationDriver
     */
    private $authorizationDriver;

    private static $unbounceAPIBaseUrl = 'https://api.unbounce.com';

    /**
     * UnbounceApiClient constructor.
     *
     * @param GuzzleClient        $guzzle
     * @param AuthorizationDriver $authorizationDriver
     */
    public function __construct(GuzzleClient $guzzle, AuthorizationDriver $authorizationDriver)
    {
        $this->guzzleClient = $guzzle;
        $this->authorizationDriver = $authorizationDriver;
    }

    /**
     * Returns all Subaccounts of the AccountId
     *
     * TODO: Filter, etc.
     *
     * @param string $accountId
     *
     * @return \Illuminate\Support\Collection
     * @throws UnbounceApiException
     */
    public function subaccounts(string $accountId)
    {
        if (empty($accountId))
        {
            throw new \InvalidArgumentException('Account Id cannot be empty');
        }

        $response = $this->get("/accounts/$accountId/sub_accounts");

        $data = json_decode((string)$response->getBody());

        $subAccounts = [];
        foreach ($data->subAccounts as $subAccountData)
        {
            $subAccounts[] = SubAccount::createFromApiData($subAccountData);
        }

        return collect($subAccounts);
    }

    /**
     * @param string $subAccountId
     *
     * @return Collection
     * @throws UnbounceApiException
     */
    public function subaccountPages(string $subAccountId)
    {
        if (empty($subAccountId))
        {
            throw new \InvalidArgumentException('SubAccount Id cannot be empty');
        }

        $response = $this->get("/sub_accounts/$subAccountId/pages");

        $data = json_decode((string)$response->getBody());

        $pages = [];
        foreach ($data->pages as $page)
        {
            $pages[] = Page::createFromApiData($page);
        }

        return collect($pages);
    }

    /**
     * @param string $uri     The Uri to append to the base Url
     * @param array  $options Array of Options
     *
     * @return ResponseInterface
     * @throws UnbounceApiException
     */
    private function get(string $uri, array $options = [])
    {
        $request = new Request('GET', UnbounceApiClient::$unbounceAPIBaseUrl . $uri);
        $request = $this->authorizationDriver->prepareRequest($request);
        $response = $this->guzzleClient->send($request);

        if ($response->getStatusCode() == 200)
        {
            return $response;
        }

        $this->runErrorHandling($response);
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
                throw new UnbounceApiException(
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