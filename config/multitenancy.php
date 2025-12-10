<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant Reference Key
    |--------------------------------------------------------------------------
    |
    | Column name in the model that associates the tenant id.
    |
    */
    'tenant_ref_key' => 'company_id',

    /*
    |--------------------------------------------------------------------------
    | Auto Resolve Tenant Id
    |--------------------------------------------------------------------------
    |
    | By default, the tenant id is automatically resolved using the authenticated user's tenant reference key defined above.
    | You can disable this feature by setting this option to false.
    | If disabled, you must manually set the tenant id resolver in your application's service provider.
    |
    | Example:
    | use Acdphp\Multitenancy\Facades\Tenancy;
    | ...
    | Tenancy::setTenantIdResolver(static function () { return 'something else'; });
    |
    */
    'auto_resolve_tenant_id' => true,
];
