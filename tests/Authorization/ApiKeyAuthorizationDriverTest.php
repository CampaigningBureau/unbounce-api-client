<?php

namespace CampaigningBureau\UnbounceApiClient\Test\Authorization;

use CampaigningBureau\UnbounceApiClient\Authorization\ApiKeyAuthorizationDriver;
use CampaigningBureau\UnbounceApiClient\Authorization\AuthorizationDriver;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class ApiKeyAuthorizationDriverTest extends TestCase
{
    var $someApiKey = 'someApiKey';

    public function testShouldImplementAuthorizationDriverInterface()
    {
        //    arrange, act
        $apiKeyAuthorizationDriver = new ApiKeyAuthorizationDriver($this->someApiKey);

        //    assert
        $this->assertInstanceOf(AuthorizationDriver::class, $apiKeyAuthorizationDriver);
    }

    public function testShouldAlterClientToUnbounceApiKeyAuthenticationOnPrepareRequest()
    {
        //    arrange
        $request = new Request('GET', 'https://api.unbounce.com');
        $apiKeyAuthorizationDriver = new ApiKeyAuthorizationDriver($this->someApiKey);

        //    act
        $request = $apiKeyAuthorizationDriver->prepareRequest($request);

        //    assert
        $expectedHeader = "Basic " . base64_encode("$this->someApiKey:");
        $this->assertEquals($expectedHeader, $request->getHeader('Authorization')[0]);
    }
}
