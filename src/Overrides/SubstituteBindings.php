<?php

namespace Acdphp\Multitenancy\Overrides;

use Acdphp\Multitenancy\Facades\Tenancy;
use Acdphp\Multitenancy\Http\Middleware\TenancyCreatingBypass;
use Acdphp\Multitenancy\Http\Middleware\TenancyScopeBypass;
use Closure;
use Illuminate\Routing\Middleware\SubstituteBindings as IlluminateSubstituteBindings;

class SubstituteBindings extends IlluminateSubstituteBindings
{
    /**
     * {@inheritDoc}
     */
    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $middlewares = $route->middleware();

        if (
            in_array(TenancyScopeBypass::class, $middlewares) ||
            in_array('tenancy.scope.bypass', $middlewares)
        ) {
            Tenancy::bypassScope();
        }

        if (
            in_array(TenancyCreatingBypass::class, $middlewares) ||
            in_array('tenancy.creating.bypass', $middlewares)
        ) {
            Tenancy::bypassCreating();
        }

        return parent::handle($request, $next);
    }
}
