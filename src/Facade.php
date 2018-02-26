<?php

namespace CampaigningBureau\UnbounceApiClient;

use Illuminate\Support\Facades\Facade as LaravelFacade;

class Facade extends LaravelFacade
{

    protected static function getFacadeAccessor()
    {
        return 'unbounce-api-client';
    }

}