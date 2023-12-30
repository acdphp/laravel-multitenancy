<?php

namespace Acdphp\Multitenancy;

use Acdphp\Multitenancy\Facades\Tenancy as TenancyFacade;
use Acdphp\Multitenancy\Http\Middleware\TenancyCreatingBypass;
use Acdphp\Multitenancy\Http\Middleware\TenancyScopeBypass;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
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
        TenancyFacade::setTenantIdResolver(static function () {
            return Auth::hasUser() ? Auth::user()->{config('multitenancy.tenant_ref_key')} : null;
        });

        // Publish config
        $this->publishes([
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'multitenancy-config');
    }
}
