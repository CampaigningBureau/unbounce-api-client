<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 14:46
 */

namespace CampaigningBureau\UnbounceApiClient\Test\Responses;


use GuzzleHttp\Psr7\Stream;

interface GuzzleResponseMock
{
    public function getBody(): Stream;
    public function getStatusCode(): int;
}