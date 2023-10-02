<?php

namespace Acdphp\Multitenancy\Scopes;

use Acdphp\Multitenancy\Facades\Tenancy;
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

        $builder->where($this->getModelTenantKey($model), Tenancy::tenantId());
    }

    public function creating(Model $model): void
    {
        if (Tenancy::creatingBypassed()) {
            return;
        }

        $model->{$this->getModelTenantKey($model)} = Tenancy::tenantId();
    }

    protected function getModelTenantKey(Model $model): string
    {
        return property_exists($model, 'tenantKey') ?
            $model->tenantKey :
            config('multitenancy.tenant_key');
    }
}
