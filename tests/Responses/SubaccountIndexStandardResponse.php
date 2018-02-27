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


class SubaccountIndexStandardResponse implements GuzzleResponseMock
{

    public static $sampleSubaccountOne = '{
                            "accountId" : "1456243",
                            "createdAt" : "2015-12-16T00:34:47.000Z",
                            "domainsCount" : 1,
                            "id" : "1552433",
                            "metadata" : {
                              "documentation" : "https://api.unbounce.com:443/doc/sub_accounts",
                              "location" : "https://api.unbounce.com:443/sub_accounts/1552433",
                              "related" : {
                                "domains" : "https://api.unbounce.com:443/sub_accounts/1552433/domains",
                                "leads" : "https://api.unbounce.com:443/sub_accounts/1552433/leads",
                                "pageGroups" : "https://api.unbounce.com:443/sub_accounts/1552433/page_groups",
                                "pages" : "https://api.unbounce.com:443/sub_accounts/1552433/pages"
                              }
                            },
                            "name" : "Default Client"
                          }';

    public static $sampleSubaccountTwo = '{
                            "accountId" : "1456243",
                            "createdAt" : "2015-12-16T00:55:54.000Z",
                            "domainsCount" : 1,
                            "id" : "1552463",
                            "metadata" : {
                              "documentation" : "https://api.unbounce.com:443/doc/sub_accounts",
                              "location" : "https://api.unbounce.com:443/sub_accounts/1552463",
                              "related" : {
                                "domains" : "https://api.unbounce.com:443/sub_accounts/1552463/domains",
                                "leads" : "https://api.unbounce.com:443/sub_accounts/1552463/leads",
                                "pageGroups" : "https://api.unbounce.com:443/sub_accounts/1552463/page_groups",
                                "pages" : "https://api.unbounce.com:443/sub_accounts/1552463/pages"
                              }
                            },
                            "name" : "Lumber James"
                          }';

    public function getBody(): Stream
    {
        return stream_for(
            '{
                          "metadata" : {
                            "count" : 2,
                            "documentation" : "https://api.unbounce.com:443/doc/sub_accounts",
                            "location" : "https://api.unbounce.com:443/accounts/1456243/sub_accounts"
                          },
                          "subAccounts" : [ ' . SubaccountIndexStandardResponse::$sampleSubaccountOne . ', ' .
            SubaccountIndexStandardResponse::$sampleSubaccountTwo . ' ],
                          "id" : "https://api.unbounce.com/raml/v0.4/examples/accounts/sub_accounts_get.json"
                        }'
        );
    }

    public function getStatusCode(): int
    {
        return 200;
    }
}