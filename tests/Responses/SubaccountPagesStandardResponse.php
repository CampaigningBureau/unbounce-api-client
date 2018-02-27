<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 14:45
 */

namespace CampaigningBureau\UnbounceApiClient\Test\Responses;


use GuzzleHttp\Psr7\Stream;
use function GuzzleHttp\Psr7\stream_for;


class SubaccountPagesStandardResponse implements GuzzleResponseMock
{

    public static $samplePageOnePublished = '{
                            "integrationsCount" : 0,
                            "url" : "http://unbouncepages.com/forward-marketing-solutions/",
                            "subAccountId" : "1552433",
                            "id" : "a0a3ffdf-5965-4ef2-8414-8afcded91f70",
                            "name" : "Forward Marketing Solutions",
                            "variantsCount" : 0,
                            "integrationsErrorsCount" : 0,
                            "domain" : "unbouncepages.com",
                            "metadata" : {
                              "documentation" : "https://api.unbounce.com:443/doc/pages",
                              "location" : "https://api.unbounce.com:443/pages/a0a3ffdf-5965-4ef2-8414-8afcded91f70",
                              "related" : {
                                "formFields" : "https://api.unbounce.com:443/pages/a0a3ffdf-5965-4ef2-8414-8afcded91f70/form_fields",
                                "leads" : "https://api.unbounce.com:443/pages/a0a3ffdf-5965-4ef2-8414-8afcded91f70/leads",
                                "subAccount" : "https://api.unbounce.com:443/sub_accounts/1552433"
                              }
                            },
                            "state" : "published",
                            "createdAt" : "2015-12-16T00:59:29.000Z",
                            "lastPublishedAt" : "2015-12-16T01:02:23.000Z",
                            "integrations" : [ ]
                          }';

    public static $samplePageTwoUmpublished = '{
                            "integrationsCount" : 0,
                            "url" : "http://forward.corporatecole.com/mastering-leather-e-book/",
                            "subAccountId" : "1552433",
                            "id" : "26d2a10b-4b00-495d-b915-978dc5b3565f",
                            "name" : "Mastering Leather E-Book",
                            "variantsCount" : 0,
                            "integrationsErrorsCount" : 0,
                            "domain" : "forward.corporatecole.com",
                            "metadata" : {
                              "documentation" : "https://api.unbounce.com:443/doc/pages",
                              "location" : "https://api.unbounce.com:443/pages/26d2a10b-4b00-495d-b915-978dc5b3565f",
                              "related" : {
                                "formFields" : "https://api.unbounce.com:443/pages/26d2a10b-4b00-495d-b915-978dc5b3565f/form_fields",
                                "leads" : "https://api.unbounce.com:443/pages/26d2a10b-4b00-495d-b915-978dc5b3565f/leads",
                                "subAccount" : "https://api.unbounce.com:443/sub_accounts/1552433"
                              }
                            },
                            "state" : "unpublished",
                            "createdAt" : "2015-12-16T01:01:28.000Z",
                            "integrations" : [ ]
                          }';

    public function getBody(): Stream
    {
        return stream_for(
            '{
                          "metadata" : {
                            "count" : 2,
                            "documentation" : "https://api.unbounce.com:443/doc/pages",
                            "location" : "https://api.unbounce.com:443/sub_accounts/1552433/pages",
                            "related" : {
                              "subAccount" : "https://api.unbounce.com:443/sub_accounts/1552433"
                            }
                          },
                          "pages" : [ ' . SubaccountPagesStandardResponse::$samplePageOnePublished . ', ' .
            SubaccountPagesStandardResponse::$samplePageTwoUmpublished . '  ],
                          "id" : "https://api.unbounce.com/raml/v0.4/examples/sub_accounts/pages_get.json"
                        }'
        );
    }

    public function getStatusCode(): int
    {
        return 200;
    }
}