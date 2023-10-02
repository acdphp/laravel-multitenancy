<?php

namespace Acdphp\Multitenancy\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @mixin \Acdphp\Multitenancy\Tenancy
 */
class Tenancy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tenancy';
    }
}
