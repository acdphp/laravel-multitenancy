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

        if ($this->isLoadedFromScopingRelation($builder, $model)) {
            return;
        }

        if ($this->getModelScopeTenancyFromRelation($model)) {
            $this->scopeFromRelation($builder, $model);
        } else {
            $this->scope($builder);
        }
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
            // Remove this scope from the subquery since whereHas already triggers it
            $builder->withoutGlobalScope(self::class);

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

    /**
     * Determine if the query is being loaded from a scoping relation.
     * Example: Product scoped from Site, Site scoped from Company.
     * Site::with('products') - products are loaded from the Site relation; we know the relation context, so no scope applies.
     * Product::with('site') - site is not scoped from product; there's no way to know the relation context, so scope applies.
     */
    protected function isLoadedFromScopingRelation(Builder $builder, Model $model): bool
    {
        $query = $builder->getQuery();
        $table = $model->getTable();
        $scopingForeignKey = $this->getScopingRelationForeignKey($model);

        if (! $table || ! $scopingForeignKey || empty($query->wheres)) {
            return false;
        }

        $matchesForeignKey = static function (array $where, string $table, string $key): bool {
            if (! isset($where['type'])) {
                return false;
            }

            $column = $table.'.'.$key;

            return (
                in_array($where['type'], ['InRaw', 'In', 'Basic'], true) &&
                isset($where['column']) &&
                $where['column'] === $column
            ) || (
                $where['type'] === 'Column' &&
                isset($where['second']) &&
                $where['second'] === $column
            );
        };

        foreach ($query->wheres as $where) {
            if ($matchesForeignKey($where, $table, $scopingForeignKey)) {
                return true;
            }

            if ($where['type'] === 'Nested' && isset($where['query'])) {
                foreach ($where['query']->wheres ?? [] as $nestedWhere) {
                    if ($matchesForeignKey($nestedWhere, $table, $scopingForeignKey)) {
                        return true;
                    }
                }
            }

            if (in_array($where['type'], ['Exists', 'NotExists'], true) && isset($where['query'])) {
                foreach ($where['query']->wheres ?? [] as $existsWhere) {
                    if ($matchesForeignKey($existsWhere, $table, $scopingForeignKey)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    protected function getScopingRelationForeignKey(Model $model): ?string
    {
        if (! ($relation = $this->getModelScopeTenancyFromRelation($model))) {
            return null;
        }

        return $model->$relation()->getForeignKeyName();
    }
}
