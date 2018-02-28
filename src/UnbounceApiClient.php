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
use Illuminate\Support\Collection;

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

        $request = $this->createGetRequest("/accounts/$accountId/sub_accounts");
        $request->withLimit(1000);

        $response = $request->send();

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

        $request = $this->createGetRequest("/sub_accounts/$subAccountId/pages");
        $request->withLimit(1000);

        $response = $request->send();

        $data = json_decode((string)$response->getBody());

        $pages = [];
        foreach ($data->pages as $page)
        {
            $pages[] = Page::createFromApiData($page);
        }

        return collect($pages);
    }

    /**
     * @param $path
     *
     * @return UnbounceApiRequest
     */
    private function createGetRequest($path)
    {
        return new UnbounceApiRequest("GET", $path, $this->guzzleClient, $this->authorizationDriver);
    }
}