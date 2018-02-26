<?php

namespace CampaigningBureau\UnbounceApiClient\Authorization;


use GuzzleHttp\Psr7\Request;

class ApiKeyAuthorizationDriver implements AuthorizationDriver
{

    /**
     * @var string $api_key The Api Key to use
     */
    var $api_key;

    /**
     * ApiKeyAuthorizationDriver constructor.
     *
     * @param string $api_key
     */
    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    public function prepareRequest(Request $request)
    {
        $credentials = "Basic " . base64_encode("$this->api_key:");
        return $request->withAddedHeader('Authorization', $credentials);
    }
}