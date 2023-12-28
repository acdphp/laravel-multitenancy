# Laravel Multitenancy
[![Latest Stable Version](https://poser.pugx.org/acdphp/laravel-multitenancy/v)](https://packagist.org/packages/acdphp/laravel-multitenancy)

Laravel multi-tenancy model scoping and automatic tenancy assignment.

## Installation
```sh
composer require acdphp/laravel-multitenancy
```

This package will resolve the current tenant based on the resolved authenticated user's tenant key value.

## Model Usage
```php
use \Acdphp\Multitenancy\Traits;

class YourModel extends Model
{
    use BelongsToTenant;
}
```

## Manually setting the tenant
- In the registration, for example, tenancy isn't set because it's a non-authenticated endpoint. The tenant has to be manually assigned.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

// Create company and set as tenant
$company = Company::create(...);
Tenancy::setTenantId($company->id);

// Then proceed to create a user
User::create(...);
```

## Bypassing Scope
- Sometimes, it's needed to bypass scoping when accessing a model that belongs to a tenant but on public endpoints. Example when logging in.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

Tenancy::bypassScope();
```

- Or by using the middleware.
```php
Route::middleware(['tenancy.scope.bypass'])->post('/login', ...);
```

## Bypassing Automatic Tenancy Assignment
- It's also possible to bypass auto-tenancy assignment.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

Tenancy::bypassCreating();
```

- Or by using the middleware.
```php
Route::middleware(['tenancy.creating.bypass'])->post('your-route', ...);
```

## Modifying config
- Publish config
```sh
php artisan vendor:publish --provider="Acdphp\Multitenancy\TenancyServiceProvider"
```

- Change column name to look for tenancy in models.
```php
'tenant_ref_key' => 'company_id',
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
