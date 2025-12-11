<?php

namespace Acdphp\Multitenancy\Scopes;

use Acdphp\Multitenancy\Facades\Tenancy;
use Acdphp\Multitenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (Tenancy::scopeBypassed()) {
            return;
        }

        // Check if this is the base/initial query model
        if ($this->isEagerLoadedRelation($builder, $model)) {
            return;
        }

        if ($this->getModelScopeTenancyFromRelation($model)) {
            $this->scopeFromRelation($builder, $model);

            return;
        }

        $this->scope($builder);
    }

    public function creating(Model $model): void
    {
        if (Tenancy::creatingBypassed() || isset($model->{$this->getModelTenantKey()})) {
            return;
        }

        $model->{$this->getModelTenantKey()} = Tenancy::tenantId();
    }

    protected function scope(Builder $builder): void
    {
        $builder->where($this->getModelTenantKey(), Tenancy::tenantId());
    }

    protected function scopeFromRelation(Builder $builder, Model $model): void
    {
        $builder->whereHas($this->getModelScopeTenancyFromRelation($model), function (Builder $builder) {
            if ($this->getModelScopeTenancyFromRelation($builder->getModel())) {
                $this->scopeFromRelation($builder, $builder->getModel());

                return;
            }

            $this->scope($builder);
        });
    }

    protected function getModelTenantKey(): string
    {
        return config('multitenancy.tenant_ref_key');
    }

    protected function getModelScopeTenancyFromRelation(Model $model): ?string
    {
        if (! method_exists($model, 'getScopeTenancyFromRelation')) {
            return null;
        }

        /**
         * @uses BelongsToTenant::getScopeTenancyFromRelation
         */
        return $model->getScopeTenancyFromRelation();
    }

    protected function isBaseQuery(Builder $builder, Model $model): bool
    {
        // If no joins exist, this is the base query
        return empty($builder->getQuery()->joins);
    }

    // Detect if the builder is being used for eager-loaded relation constraints
    protected function isEagerLoadedRelation(Builder $builder, Model $model): bool
    {
        $query = $builder->getQuery();

        // When eager loading, Laravel typically adds Nested where constraints for relation queries.
        if (! empty($query->wheres)) {
            foreach ($query->wheres as $where) {
                if (isset($where['type']) && $where['type'] === 'InRaw') {
                    return true;
                }
            }
        }

        return false;
    }
}
