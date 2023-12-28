<?php

namespace Acdphp\Multitenancy\Traits;

use Acdphp\Multitenancy\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    public function initializeBelongsToTenant(): void
    {
        $this->fillable[] = config('multitenancy.tenant_ref_key');
    }

    public static function bootBelongsToTenant(): void
    {
        $scope = new TenantScope();

        static::addGlobalScope($scope);

        static::creating(static fn (Model $model) => $scope->creating($model));
    }
}
