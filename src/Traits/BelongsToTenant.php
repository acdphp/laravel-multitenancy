<?php

namespace Acdphp\Multitenancy\Traits;

use Acdphp\Multitenancy\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $scopeTenancyFromRelation
 */
trait BelongsToTenant
{
    public function initializeBelongsToTenant(): void
    {
        $tenantRefKey = config('multitenancy.tenant_ref_key');

        if (! $this->getScopeTenancyFromRelation() && ! in_array($tenantRefKey, $this->fillable)) {
            $this->fillable[] = $tenantRefKey;
        }
    }

    public static function bootBelongsToTenant(): void
    {
        $scope = new TenantScope;

        static::addGlobalScope($scope);

        static::creating(static function (Model $model) use ($scope) {
            if (call_user_func([$model, 'getScopeTenancyFromRelation'])) {
                return;
            }

            $scope->creating($model);
        });
    }

    public function getScopeTenancyFromRelation(): ?string
    {
        return $this->scopeTenancyFromRelation ?? null;
    }
}
