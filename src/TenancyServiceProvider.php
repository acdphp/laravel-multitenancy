<?php

namespace Acdphp\Multitenancy;

use Acdphp\Multitenancy\Http\Middleware\TenancyCreatingBypass;
use Acdphp\Multitenancy\Http\Middleware\TenancyScopeBypass;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class TenancyServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // Register Tenancy
        $this->app->singleton('tenancy', function (Application $app) {
            $service = new Tenancy();

            if (! $app->runningInConsole() && ($user = $app->get('request')->user())) {
                $service->setTenant($user->{config('multitenancy.tenant_ref_key')});
            }

            return $service;
        });

        // Config merge
        $this->mergeConfigFrom(
            __DIR__.'/../config/multitenancy.php',
            'multitenancy'
        );
    }

    public function boot(Router $router): void
    {
        // Middleware alias
        $router->aliasMiddleware('tenancy.scope.bypass', TenancyScopeBypass::class);
        $router->aliasMiddleware('tenancy.creating.bypass', TenancyCreatingBypass::class);

        // Publish config
        $this->publishes([
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'multitenancy-config');
    }
}
