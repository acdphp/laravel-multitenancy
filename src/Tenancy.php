<?php

namespace Acdphp\Multitenancy;

use Acdphp\Multitenancy\Exceptions\InvalidTenantClassException;
use Illuminate\Database\Eloquent\Model;

class Tenancy
{
    protected Model $tenant;

    protected bool $scopeBypass = false;

    protected bool $creatingBypass = false;

    public function tenant(): Model
    {
        return $this->tenant;
    }

    public function tenantId(): int|string
    {
        return $this->tenant()->{config('multitenancy.tenant_primary_key')};
    }

    /**
     * @throws InvalidTenantClassException
     */
    public function setTenant(Model $model): self
    {
        if (! is_a($model, config('multitenancy.tenant_class'))) {
            throw new InvalidTenantClassException();
        }

        $this->tenant = $model;

        return $this;
    }

    public function scopeBypassed(): bool
    {
        return $this->scopeBypass;
    }

    public function bypassScope(): void
    {
        $this->scopeBypass = true;
    }

    public function creatingBypassed(): bool
    {
        return $this->creatingBypass;
    }

    public function bypassCreating(): void
    {
        $this->creatingBypass = true;
    }
}
