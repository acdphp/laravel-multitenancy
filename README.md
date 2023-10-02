# Laravel Multitenancy
[![Latest Stable Version](https://poser.pugx.org/acdphp/laravel-multitenancy/v)](https://packagist.org/packages/cdinopol/data-guard)
[![License](http://poser.pugx.org/acdphp/laravel-multitenancy/license)](https://packagist.org/packages/cdinopol/data-guard)

Laravel multi-tenancy model scoping and automatic tenancy assignment.

## Installation
```sh
composer require acdphp/laravel-multitenancy
```

## Modifying config
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
- Sometimes, it's needed to bypass scoping when there's no source yet for tenancy. Example when logging in.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

Tenancy::bypassScope();
```

- Or by using the middleware.
```php
Route::middleware(['tenancy.scope.bypass'])->post('/login', ...);
```

## Bypassing Automatic Tenancy Assignment
- This should be avoided to prevent stray models that should belong to a tenant. But if needed, you may also bypass it.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

Tenancy::bypassCreating();
```

- Or by using the middleware.
```php
Route::middleware(['tenancy.creating.bypass'])->post(...);
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
