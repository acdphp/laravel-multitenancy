<?php

namespace Acdphp\Multitenancy;

class Tenancy
{
    protected int|string $tenantId;

    protected bool $scopeBypass = false;

    protected bool $creatingBypass = false;

    public function tenantId(): int|string
    {
        return $this->tenantId;
    }

    public function setTenantId(int|string $tenantId): self
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    public function scopeBypassed(): bool
    {
        return $this->scopeBypass ||
            (! isset($this->tenantId) && app()->runningInConsole());
    }

    public function bypassScope(): void
    {
        $this->scopeBypass = true;
    }

    public function creatingBypassed(): bool
    {
        return $this->creatingBypass ||
            (! isset($this->tenantId) && app()->runningInConsole());
    }

    public function bypassCreating(): void
    {
        $this->creatingBypass = true;
    }
}
