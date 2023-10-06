<?php

namespace Acdphp\Multitenancy\Http\Middleware;

use Acdphp\Multitenancy\Facades\Tenancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TenancyScopeBypass
{
    public function handle(Request $request, \Closure $next): Response|RedirectResponse|JsonResponse
    {
        Tenancy::bypassScope();

        return $next($request);
    }
}
