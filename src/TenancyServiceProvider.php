<?php

namespace Acdphp\Multitenancy;

use Acdphp\Multitenancy\Http\Middleware\InjectTenancyFromAuth;
use Acdphp\Multitenancy\Http\Middleware\TenancyCreatingBypass;
use Acdphp\Multitenancy\Http\Middleware\TenancyScopeBypass;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class TenancyServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // Register Tenancy
        $this->app->singleton('tenancy', function () {
            return new Tenancy();
        });

        // Config merge
        $this->mergeConfigFrom(
            __DIR__.'/../config/multitenancy.php',
            'multitenancy'
        );
    }

    public function boot(Router $router, Kernel $kernel): void
    {
        // Middleware alias
        $router->aliasMiddleware('tenancy.scope.bypass', TenancyScopeBypass::class);
        $router->aliasMiddleware('tenancy.creating.bypass', TenancyCreatingBypass::class);

        // Append middleware
        $kernel->pushMiddleware(InjectTenancyFromAuth::class);

        // Publish config
        $this->publishes([
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'multitenancy-config');
    }
}
