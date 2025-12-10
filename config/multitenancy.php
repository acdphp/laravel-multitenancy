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

    /*
    |--------------------------------------------------------------------------
    | Auto Assign Tenant Id
    |--------------------------------------------------------------------------
    |
    | By default, the tenant id is automatically assigned to models that use the BelongsToTenant trait and have direct relation to the tenant model during creation.
    | You can disable this globally by setting this option to false.
    | If disabled, you must manually set the tenant id as you would normally set it on model creation.
    |
    | Example:
    |
    | YourModel::create([
    |     'company_id' => // Assign tenant id manually,
    |     // other attributes...
    | ]);
    |
    */
    'auto_assign_tenant_id' => true,
];
