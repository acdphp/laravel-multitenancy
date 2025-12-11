<?php

namespace Acdphp\Multitenancy;

use Closure;

class TenancyManager
{
    protected int|string|null $tenantId;

    protected bool $tenantIdWasResolved = false;

    protected bool $scopeBypass = false;

    protected bool $creatingBypass = false;

    protected Closure $tenantIdResolver;

    public function tenantId(): int|string|null
    {
        if (! isset($this->tenantIdResolver)) {
            return null;
        }

        if (! $this->tenantIdWasResolved) {
            $this->tenantIdWasResolved = true;
            $this->tenantId = call_user_func($this->getTenantIdResolver());
        }

        return $this->tenantId;
    }

    public function getTenantIdResolver(): Closure
    {
        return $this->tenantIdResolver;
    }

    public function setTenantIdResolver(Closure $tenantIdResolver): self
    {
        $this->tenantIdResolver = $tenantIdResolver;

        $this->forgetTenant();

        return $this;
    }

    public function scopeBypassed(): bool
    {
        return $this->scopeBypass || ! $this->tenantId();
    }

    public function bypassScope(): void
    {
        $this->scopeBypass = true;
    }

    public function creatingBypassed(): bool
    {
        return $this->creatingBypass || ! $this->tenantId();
    }

    public function bypassCreating(): void
    {
        $this->creatingBypass = true;
    }

    public function forgetTenant(): void
    {
        $this->tenantIdWasResolved = false;
        $this->tenantId = null;
    }
}
