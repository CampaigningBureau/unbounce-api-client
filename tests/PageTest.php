<?php

namespace CampaigningBureau\UnbounceApiClient\Test;

use CampaigningBureau\UnbounceApiClient\Page;
use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountPagesStandardResponse;
use Carbon\Carbon;

class PageTest extends TestCase
{

    public function testShouldBeCreateableFromAPIData()
    {
        //    arrange
        $sampleAPIData = json_decode(SubaccountPagesStandardResponse::$samplePageOnePublished);

        //    act
        $page = Page::createFromApiData($sampleAPIData);

        //    assert
        $this->assertEquals('a0a3ffdf-5965-4ef2-8414-8afcded91f70', $page->getId());
    }

    public function testShouldSetDataFromCreatorMethod()
    {
        //    arrange
        $sampleAPIData = json_decode(SubaccountPagesStandardResponse::$samplePageOnePublished);

        $expectedCreatedAt = new Carbon("2015-12-16T00:59:29.000Z");
        $expectedLastPublishedAt = new Carbon("2015-12-16T01:02:23.000Z");

        //    act
        $page = Page::createFromApiData($sampleAPIData);

        //    assert

        $this->assertEquals("Forward Marketing Solutions", $page->getName());
        $this->assertEquals(0, $page->getIntegrationsCount());
        $this->assertEquals("http://unbouncepages.com/forward-marketing-solutions/", $page->getUrl());
        $this->assertEquals("unbouncepages.com", $page->getDomain());
        $this->assertEquals("1552433", $page->getSubAccountId());
        $this->assertEquals(0, $page->getVariantsCount());
        $this->assertEquals(0, $page->getIntegrationErrorsCount());
        $this->assertEquals("published", $page->getState());
        $this->assertEquals($expectedCreatedAt, $page->getCreatedAt());
        $this->assertEquals($expectedLastPublishedAt, $page->getLastPublishedAt());
    }

    public function testShouldProvideFunctionToCheckIfIsPublished()
    {
        //    arrange
        $sampleAPIDataPublished = json_decode(SubaccountPagesStandardResponse::$samplePageOnePublished);
        $sampleAPIDataUnpublished = json_decode(SubaccountPagesStandardResponse::$samplePageTwoUmpublished);

        //    act
        $pagePublished = Page::createFromApiData($sampleAPIDataPublished);
        $pageUnpublished = Page::createFromApiData($sampleAPIDataUnpublished);

        //    assert
        $this->assertTrue($pagePublished->isPublished());
        $this->assertFalse($pageUnpublished->isPublished());
    }
}
