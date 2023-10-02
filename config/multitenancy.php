<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant Class
    |--------------------------------------------------------------------------
    |
    | Usually the company class. This class will be automatically associated
    | to the classes that uses HasTenantTrait. This class must contain a primary
    | key.
    |
    */
    'tenant_class' => \App\Models\Company::class,

    /*
    |--------------------------------------------------------------------------
    | Tenant Key
    |--------------------------------------------------------------------------
    |
    | Column name of the model that associates the tenant class.
    |
    */
    'tenant_key' => 'company_id',
];
