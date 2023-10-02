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
    | Tenant Primary Key
    |--------------------------------------------------------------------------
    |
    | Column name of the tenant model.
    |
    */
    'tenant_primary_key' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Tenant Reference Key
    |--------------------------------------------------------------------------
    |
    | Column name of the model that associates the tenant class.
    |
    */
    'tenant_ref_key' => 'company_id',
];
