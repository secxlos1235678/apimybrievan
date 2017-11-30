<?php

namespace App\Classes\Client\Facades;

use Illuminate\Support\Facades\Facade;

class DbwsRest extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bri.dbwsrest';
    }
}
