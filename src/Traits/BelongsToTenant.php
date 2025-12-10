<?php

namespace Acdphp\Multitenancy\Traits;

use Acdphp\Multitenancy\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

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

        // Scoping
        static::addGlobalScope($scope);

        // Auto-assign tenant ID on creating
        static::creating(static function (Model $model) use ($scope) {
            // Global config to disable auto-assigning tenant ID
            if (config('multitenancy.auto_assign_tenant_id') === false) {
                return;
            }

            /**
             * Skip if model is scoped from relationship
             *
             * @uses getScopeTenancyFromRelation()
             */
            if (call_user_func([$model, 'getScopeTenancyFromRelation'])) {
                return;
            }

            /**
             * Skip if model has autoAssignTenantId set to false
             *
             * @uses getAutoAssignTenantId()
             */
            if (call_user_func([$model, 'getAutoAssignTenantId']) === false) {
                return;
            }

            $scope->creating($model);
        });
    }

    public function getScopeTenancyFromRelation(): ?string
    {
        return $this->scopeTenancyFromRelation ?? null;
    }

    public function getAutoAssignTenantId(): bool
    {
        return $this->autoAssignTenantId ?? true;
    }
}
