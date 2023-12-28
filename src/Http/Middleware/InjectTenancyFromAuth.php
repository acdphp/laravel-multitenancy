<?php

namespace Acdphp\Multitenancy\Http\Middleware;

use Acdphp\Multitenancy\Facades\Tenancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InjectTenancyFromAuth
{
    public function handle(Request $request, \Closure $next): Response|RedirectResponse|JsonResponse
    {
        if (($user = $request->user())) {
            Tenancy::setTenantId($user->{config('multitenancy.tenant_ref_key')});
        }

        return $next($request);
    }
}
