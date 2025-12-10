<?php

namespace Acdphp\Multitenancy\Facades;

use Acdphp\Multitenancy\TenancyManager;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin TenancyManager
 */
class Tenancy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tenancy';
    }
}
