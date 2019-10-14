<?php

namespace CampaigningBureau\UnbounceApiClient\Test;

use CampaigningBureau\UnbounceApiClient\Authorization\ApiKeyAuthorizationDriver;
use CampaigningBureau\UnbounceApiClient\Page;
use CampaigningBureau\UnbounceApiClient\SubAccount;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SinglePageStandardResponse;
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

    public function testSubaccountPagesReturnsPages()
    {
        //    arrange
        $response = new SubaccountPagesStandardResponse();
        $this->mockUnbounceApi($response);

        //    act
        /** @var Collection $pages */
        $pages = Unbounce::subaccountPages("the_subaccount_id");

        //    assert
        $this->assertInstanceOf(Collection::class, $pages);
        /** @var Page $firstPage */
        $firstPage = $pages->first();
        $this->assertInstanceOf(Page::class, $firstPage);
        $this->assertEquals('Forward Marketing Solutions', $firstPage->getName());
    }

    public function testSubaccountPagesReturnsFullyLoadedPagesOnFlag()
    {
        //    arrange
        $response = new SubaccountPagesStandardResponse();
        $followUpResponses = new SinglePageStandardResponse();
        $this->mockUnbounceApiWithMultipleRequests([$response, $followUpResponses]);

        //    act
        /** @var Collection $pages */
        $pages = Unbounce::subaccountPages("the_subaccount_id", true);

        //    assert
        $this->assertInstanceOf(Collection::class, $pages);
        /** @var Page $firstPage */
        $firstPage = $pages->first();
        $this->assertInstanceOf(Page::class, $firstPage);
        $this->assertEquals('Forward Marketing Solutions', $firstPage->getName());
        $this->assertEquals(33.33, $firstPage->getConversionRate());
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

    public function testPageReturnsSinglePage()
    {
        //    arrange
        $response = new SinglePageStandardResponse();
        $this->mockUnbounceApi($response);

        //    act
        /** @var Page $page */
        $page = Unbounce::page("the_page_id");

        //    assert
        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals('Forward Marketing Solutions', $page->getName());
        $this->assertEquals(33.33, $page->getConversionRate());
    }
}
