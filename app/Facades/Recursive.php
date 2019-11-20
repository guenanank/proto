<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Recursive extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'recursive';
    }
}
