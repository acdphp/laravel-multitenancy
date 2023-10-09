# Laravel Multitenancy
[![Latest Stable Version](https://poser.pugx.org/acdphp/laravel-multitenancy/v)](https://packagist.org/packages/cdinopol/data-guard)
[![License](http://poser.pugx.org/acdphp/laravel-multitenancy/license)](https://packagist.org/packages/cdinopol/data-guard)

Laravel multi-tenancy model scoping and automatic tenancy assignment.

## Installation
```sh
composer require acdphp/laravel-multitenancy
```

## Modifying config
- By default, it will look for `\App\Models\Company::class` as `tenant_class` that has `id` as `tenant_primary_key`. Relatively, it will use `company_id` column of the models that belongs to a tenant as `tenant_ref_key`. If this isn't the case, you may override the config by publishing it.
```sh
php artisan vendor:publish --provider="Acdphp\Multitenancy\TenancyServiceProvider"
```

## Model Usage
```php
use \Acdphp\Multitenancy\Traits;

class YourModel extends Model
{
    use BelongsToTenant;
}
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

## Manually setting tenant
- In the registration, for example, tenancy isn't set because it's a non-authenticated endpoint. The tenant has to be manually assigned using setTenant.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

// Create company and set as tenant
$company = Company::create(...);
Tenancy::setTenant($company);

// Then proceed to create a user
User::create(...);
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
