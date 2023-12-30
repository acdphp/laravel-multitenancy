<?php

namespace Acdphp\Multitenancy;

use Closure;

class Tenancy
{
    protected bool $scopeBypass = false;

    protected bool $creatingBypass = false;

    protected Closure $tenantIdResolver;

    public function tenantId(): int|string|null
    {
        return call_user_func($this->getTenantIdResolver());
    }

    public function getTenantIdResolver(): Closure
    {
        return $this->tenantIdResolver;
    }

    public function setTenantIdResolver(Closure $tenantIdResolver): self
    {
        $this->tenantIdResolver = $tenantIdResolver;

        return $this;
    }

    public function scopeBypassed(): bool
    {
        return $this->scopeBypass ||
            (! $this->tenantId() && app()->runningInConsole() && ! app()->runningUnitTests());
    }

    public function bypassScope(): void
    {
        $this->scopeBypass = true;
    }

    public function creatingBypassed(): bool
    {
        return $this->creatingBypass ||
            (! $this->tenantId() && app()->runningInConsole() && ! app()->runningUnitTests());
    }

    public function bypassCreating(): void
    {
        $this->creatingBypass = true;
    }
}
