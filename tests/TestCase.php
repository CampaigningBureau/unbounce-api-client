<?php

namespace CampaigningBureau\UnbounceApiClient\Test;

use CampaigningBureau\UnbounceApiClient\Authorization\ApiKeyAuthorizationDriver;
use CampaigningBureau\UnbounceApiClient\Facade;
use CampaigningBureau\UnbounceApiClient\ServiceProvider;
use CampaigningBureau\UnbounceApiClient\Test\Responses\GuzzleResponseMock;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountIndexStandardResponse;
use CampaigningBureau\UnbounceApiClient\UnbounceApiClient;
use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{

    /**
     * @param \Illuminate\Foundation\Application $application
     *
     * @return array
     */
    protected function getPackageProviders($application)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($application)
    {
        return [
            'Unbounce' => Facade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('unbounce.api_key', 'some_api_key');
    }


    /**
     * @param GuzzleResponseMock $unbounceResponse The Response to be returned
     * @param int|null           $times
     */
    protected function mockUnbounceApi(GuzzleResponseMock $unbounceResponse = null, int $times = null)
    {
        // If no Response is needed, take any
        $unbounceResponse = $unbounceResponse ?? new SubaccountIndexStandardResponse();

        $this->app->bind(
            UnbounceApiClient::class,
            function () use ($times, $unbounceResponse)
            {
                $guzzle_mock = Mockery::mock('GuzzleHttp\Client');
                $guzzle_mock->shouldReceive('send')
                            ->times($times)
                            ->andReturn($unbounceResponse);

                return new UnbounceApiClient($guzzle_mock, new ApiKeyAuthorizationDriver('sample_api_key'));
            }
        );

    }
}