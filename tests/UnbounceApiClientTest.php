<?php

namespace CampaigningBureau\UnbounceApiClient\Test;

use CampaigningBureau\UnbounceApiClient\Authorization\ApiKeyAuthorizationDriver;
use CampaigningBureau\UnbounceApiClient\Page;
use CampaigningBureau\UnbounceApiClient\SubAccount;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountIndexStandardResponse;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountIndexUnauthorizedResponse;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountPagesStandardResponse;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountPagesUnauthorizedResponse;
use CampaigningBureau\UnbounceApiClient\UnauthorizedApiException;
use CampaigningBureau\UnbounceApiClient\UnbounceApiClient;
use Illuminate\Support\Collection;
use Mockery;
use Unbounce;

class UnbounceApiClientTest extends TestCase
{

    private $sampleAuthorizationDriver;


    public function __construct()
    {
        parent::__construct();
        $this->sampleAuthorizationDriver = new ApiKeyAuthorizationDriver('sample_api_key');
    }


    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testClientAcceptsApiKeyAuthorizationDriver()
    {
        //    arrange
        $guzzle_mock = Mockery::mock('GuzzleHttp\Client');
        $any_api_key = 'some_random_string';

        //    act
        new UnbounceApiClient($guzzle_mock, new ApiKeyAuthorizationDriver($any_api_key));

        //    assert
        $this->assertTrue(true);
    }

    public function testSubaccountsReturnsCollectionOfTypeSubaccount()
    {
        //    arrange
        $this->mockUnbounceApi(new SubaccountIndexStandardResponse());

        //    act
        /** @var $subaccounts Collection */
        $subaccounts = Unbounce::subaccounts("the_account_id");

        //    assert
        $this->assertInstanceOf(Collection::class, $subaccounts);
        /** @var SubAccount $firstSubaccount */
        $firstSubaccount = $subaccounts->first();
        $this->assertInstanceOf(Subaccount::class, $firstSubaccount);
        //    assert Subaccount Data from First Subaccount of StandardResponse
        $this->assertEquals('Default Client', $firstSubaccount->getName());
    }

    public function testShouldThrowUnauthorizedApiExceptionWhenUnauthorizedCallOnSubaccounts()
    {
        //    arrange
        $this->mockUnbounceApi(new SubaccountIndexUnauthorizedResponse());
        $this->expectException(UnauthorizedApiException::class);
        $this->expectExceptionCode(401);

        //    act
        Unbounce::subaccounts("the_account_id");
        //    assert
    }

    public function testPagesReturnsCollectionOfTypePageForSpecificSubaccount()
    {
        //    arrange
        $response = new SubaccountPagesStandardResponse();
        $this->mockUnbounceApi($response);

        //    act
        /** @var Collection $pages */
        $pages = Unbounce::subaccountPages("the_subaccount_id");

        //    assert
        $this->assertInstanceOf(Collection::class, $pages);
        $firstPage = $pages->first();
        $this->assertInstanceOf(Page::class, $firstPage);
        $this->assertEquals('Forward Marketing Solutions', $firstPage->getName());
    }

    public function testtestShouldThrowUnauthorizedApiExceptionWhenUnauthorizedCallOnSubaccountPages()
    {

        //    arrange
        $this->mockUnbounceApi(new SubaccountPagesUnauthorizedResponse());
        $this->expectException(UnauthorizedApiException::class);
        $this->expectExceptionCode(401);

        //    act
        Unbounce::subaccountPages("the_subaccount_id");
        //    assert
    }
}
