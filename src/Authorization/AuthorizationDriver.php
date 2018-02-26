<?php

namespace CampaigningBureau\UnbounceApiClient\Authorization;


use GuzzleHttp\Psr7\Request;

interface AuthorizationDriver
{

    public function prepareRequest(Request $request);

}