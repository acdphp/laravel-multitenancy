# Laravel Multitenancy
[![Latest Stable Version](https://poser.pugx.org/acdphp/laravel-multitenancy/v)](https://packagist.org/packages/acdphp/laravel-multitenancy)

Laravel multi-tenancy model scoping and automatic tenancy assignment.

## Installation
```sh
composer require acdphp/laravel-multitenancy
```

This package will resolve the current tenant based on the resolved authenticated user's tenant key value.

## Example Usage
```php
use \Acdphp\Multitenancy\Traits\BelongsToTenant;

class Site extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'company_id',
        ...
    ];
}
```

## Scoping from parent relationship
```php
use \Acdphp\Multitenancy\Traits\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'site_id',
        ...
    ];
    
    protected string $scopeTenancyFromRelation = 'site'; // Define to scope from parent model
    
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
```

---

## Manually setting the tenant
- In the registration, for example, tenancy isn't set because it's a non-authenticated endpoint. The tenant has to be manually assigned.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

// Create a company and set it as tenant
$company = Company::create(...);
Tenancy::setTenantIdResolver(fn () => $company->id);

// Then proceed to create a user
User::create(...);
```

## Bypassing Scope
- Sometimes, it's needed to bypass scoping when accessing a model that belongs to a tenant.
```php
use Acdphp\Multitenancy\Facades\Tenancy;

Tenancy::bypassScope();
```

- Or by using the middleware.
```php
Route::middleware(['tenancy.scope.bypass'])->get('/resources/all', ...);
```

## Bypassing Automatic Tenancy Assignment
- It's also possible to bypass auto-tenancy assignments.
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

- Change the column name to look for tenancy in models.
```php
'tenant_ref_key' => 'company_id',
```

---

## Testing
```sh
./vendor/bin/pest
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
