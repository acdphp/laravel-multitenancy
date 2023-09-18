# Laravel Multitenancy
[![Latest Stable Version](https://poser.pugx.org/acdphp/laravel-multitenancy/v)](https://packagist.org/packages/cdinopol/data-guard)
[![License](http://poser.pugx.org/acdphp/laravel-multitenancy/license)](https://packagist.org/packages/cdinopol/data-guard)

Laravel multi-tenancy model scoping and automatic tenancy assignment.

## Installation
```sh
composer require acdphp/laravel-multitenancy
```

## Model Usage
```php
use \Acdphp\Multitenancy\Traits;

class YourModel extends Model
{
    use BelongsToTenant;
}
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
