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


class SubaccountPagesEmptyResponse implements GuzzleResponseMock
{

    public function getBody(): Stream
    {
        return stream_for(
            '{
                          "metadata" : {
                            "count" : 0,
                            "documentation" : "https://api.unbounce.com:443/doc/pages",
                            "location" : "https://api.unbounce.com:443/sub_accounts/1552433/pages",
                            "related" : {
                              "subAccount" : "https://api.unbounce.com:443/sub_accounts/1552433"
                            }
                          },
                          "pages" : [],
                          "id" : "https://api.unbounce.com/raml/v0.4/examples/sub_accounts/pages_get.json"
                        }'
        );
    }

    public function getStatusCode(): int
    {
        return 200;
    }
}