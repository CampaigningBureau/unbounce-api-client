<?php

namespace CampaigningBureau\UnbounceApiClient\Test;

use CampaigningBureau\UnbounceApiClient\Page;
use CampaigningBureau\UnbounceApiClient\SubAccount;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountIndexStandardResponse;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountPagesEmptyResponse;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountPagesStandardResponse;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountPagesUnauthorizedResponse;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SubAccountTest extends TestCase
{

    public function testShouldBeCreateableFromAPIData()
    {
        //    arrange
        $sampleAPIData = json_decode(SubaccountIndexStandardResponse::$sampleSubaccountOne);

        //    act
        $subaccount = Subaccount::createFromApiData($sampleAPIData);

        //    assert
        $this->assertEquals('1552433', $subaccount->getId());
    }

    public function testCreatorFunctionThrowsErrorIfNoIdGiven()
    {
        //    assert
        $this->expectException(\InvalidArgumentException::class);

        //    act
        Subaccount::createFromApiData('');
    }

    public function testShouldSetNameFromCreatorMethod()
    {
        //    arrange
        $sampleAPIData = json_decode(SubaccountIndexStandardResponse::$sampleSubaccountOne);

        //    act
        $subaccount = Subaccount::createFromApiData($sampleAPIData);

        //    assert
        // $this->assertEquals(1, $subaccount->getDomainCount());
        $this->assertEquals("Default Client", $subaccount->getName());
    }

    public function testShouldSetDomainCountFromCreatorMethod()
    {
        //    arrange
        $sampleAPIData = json_decode(SubaccountIndexStandardResponse::$sampleSubaccountOne);

        //    act
        $subaccount = Subaccount::createFromApiData($sampleAPIData);

        //    assert
        $this->assertEquals(1, $subaccount->getDomainsCount());
    }

    public function testShouldSetCreatedAtFromCreatorMethod()
    {
        //    arrange
        $sampleAPIData = json_decode(SubaccountIndexStandardResponse::$sampleSubaccountOne);

        //    act
        $subaccount = Subaccount::createFromApiData($sampleAPIData);

        //    assert
        $expectedCreatedAt = new Carbon("2015-12-16T00:34:47.000Z");
        $this->assertEquals($expectedCreatedAt, $subaccount->getCreatedAt());
    }

    public function testShouldSetAccountIdFromCreatorMethod()
    {
        //    arrange
        $sampleAPIData = json_decode(SubaccountIndexStandardResponse::$sampleSubaccountOne);

        //    act
        $subaccount = Subaccount::createFromApiData($sampleAPIData);

        //    assert
        $this->assertEquals("1456243", $subaccount->getAccountId());
    }

    public function testShouldHaveAMethodToGetAllPages()
    {
        //    arrange
        $response = new SubaccountPagesStandardResponse();
        $this->mockUnbounceApi($response);
        $subaccount = new SubAccount('some_id', 'some_accountId');

        //    act
        /** @var Collection $pages */
        $pages = $subaccount->getPages();

        //    assert
        $this->assertInstanceOf(Collection::class, $pages);
        $this->assertEquals(2, $pages->count());
        $this->assertInstanceOf(Page::class, $pages->first());
    }

    public function testShouldReturnEmptyCollectionIfNoPagesFound()
    {
        //    arrange
        $response = new SubaccountPagesEmptyResponse();
        $this->mockUnbounceApi($response);
        $subaccount = new SubAccount('some_id', 'some_accountId');

        //    act
        /** @var Collection $pages */
        $pages = $subaccount->getPages();

        //    assert
        $this->assertInstanceOf(Collection::class, $pages);
        $this->assertEquals(0, $pages->count());
    }

    public function testShouldNotCallTheApiIfPagesAreAlreadyLoaded()
    {
        //    arrange
        $response = new SubaccountPagesStandardResponse();
        // the test fails, if the mocked-api-method is called more than once
        $this->mockUnbounceApi($response, 1);
        $subaccount = new SubAccount('some_id', 'some_accountId');

        //    act
        $subaccount->getPages();
        $subaccount->getPages();

        //    assert
        $this->assertTrue(true);
    }

    public function testShouldNotQueryTheApiForPagesIfAPageWasAdded()
    {
        //    arrange
        $response = new SubaccountPagesStandardResponse();
        // the test fails, if the mocked-api-method is called more than once
        $this->mockUnbounceApi($response, 0);
        $subaccount = new SubAccount('some_id', 'some_accountId');

        //    act
        $subaccount->addPage(new Page('some_id'));
        $subaccount->getPages();

        //    assert
        $this->assertTrue(true);
    }

    public function testShouldReloadThePagesFromApiWhenUsingReloadPages()
    {
        //    arrange
        $response = new SubaccountPagesStandardResponse();
        // the test fails, if the mocked-api-method is called less than twice
        $this->mockUnbounceApi($response, 2);
        $subaccount = new SubAccount('some_id', 'some_accountId');

        //    act
        $subaccount->getPages();
        $subaccount->reloadPages();

        //    assert
        $this->assertTrue(true);
    }

    public function testShouldHaveAStateUnkownAfterCreation()
    {
        // Unfortunately, the Unbounce API has no method to deliver the state of a subaccount
        // only when getting a 401 unauthorized after loading subroutes, we know it is not active.

        //    arrange
        $expected_state = SubAccount::stateUnknown;

        //    act
        $subaccount = new SubAccount('some_id', 'some_account_id');

        //    assert
        $this->assertEquals($expected_state, $subaccount->getState());
    }

    public function testShouldHaveAStateOfActiveAfterSuccessfulGetPagesCall()
    {
        //    arrange
        $expected_state = SubAccount::stateActive;
        $subaccount = new SubAccount('some_id', 'some_account_id');
        $this->mockUnbounceApi(new SubaccountPagesStandardResponse());

        //    act
        $subaccount->getPages();

        //    assert
        $this->assertEquals($expected_state, $subaccount->getState());
    }

    public function testShouldHaveAStateOfInactiveAfterUnauthorizedGetPagesCall()
    {
        //    arrange
        $expected_state = SubAccount::stateInactive;
        $subaccount = new SubAccount('some_id', 'some_account_id');
        $this->mockUnbounceApi(new SubaccountPagesUnauthorizedResponse());

        //    act
        $subaccount->getPages();

        //    assert
        $this->assertEquals($expected_state, $subaccount->getState());
    }

    public function testShouldHaveTheCorrectCountOfPublishedPagesAfterGetPagesCall()
    {
        //    arrange
        $subaccount = new SubAccount('some_id', 'some_account_id');
        $this->mockUnbounceApi(new SubaccountPagesStandardResponse());

        //    act
        $subaccount->getPages();

        //    assert
        $this->assertEquals(1, $subaccount->getPublishedPageCount());
    }

    public function testShouldHaveTheCorrectCountOfUnpublishedPagesAfterGetPagesCall()
    {
        //    arrange
        $subaccount = new SubAccount('some_id', 'some_account_id');
        $this->mockUnbounceApi(new SubaccountPagesStandardResponse());

        //    act
        $subaccount->getPages();

        //    assert
        $this->assertEquals(1, $subaccount->getUnpublishedPageCount());
    }

    public function testShouldHaveTheCorrectCountOfPagesAfterGetPagesCall()
    {
        //    arrange
        $subaccount = new SubAccount('some_id', 'some_account_id');
        $this->mockUnbounceApi(new SubaccountPagesStandardResponse());

        //    act
        $subaccount->getPages();

        //    assert
        $this->assertEquals(2, $subaccount->getPageCount());
    }
}
