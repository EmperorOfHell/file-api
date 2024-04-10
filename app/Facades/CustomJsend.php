<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CustomJsend extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CustomJsend';
    }
}
