<?php

namespace Acdphp\Multitenancy;

use Closure;

class TenancyManager
{
    private int|string|null $tenantId = null;

    /**
     * @var int[]|string[]|int|string|null
     */
    private array|int|string|null $scopingTenantId = null;

    private bool $tenantIdWasResolved = false;

    private bool $scopingTenantIdWasResolved = false;

    private bool $scopeBypass = false;

    private bool $creatingBypass = false;

    private Closure $tenantIdResolver;

    private Closure $scopingTenantIdResolver;

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

    /**
     * @return int[]|string[]|int|string|null
     */
    public function scopingTenantId(): array|int|string|null
    {
        if (! isset($this->scopingTenantIdResolver)) {
            return $this->tenantId();
        }

        if (! $this->scopingTenantIdWasResolved) {
            $this->scopingTenantIdWasResolved = true;
            $this->scopingTenantId = call_user_func($this->getScopingTenantIdResolver());
        }

        return $this->scopingTenantId;
    }

    public function getTenantIdResolver(): Closure
    {
        return $this->tenantIdResolver;
    }

    public function getScopingTenantIdResolver(): Closure
    {
        return $this->scopingTenantIdResolver;
    }

    public function setTenantIdResolver(Closure $tenantIdResolver): self
    {
        $this->tenantIdResolver = $tenantIdResolver;

        $this->forgetTenant();

        return $this;
    }

    public function setScopingTenantIdResolver(Closure $scopingTenantIdResolver): self
    {
        $this->scopingTenantIdResolver = $scopingTenantIdResolver;

        $this->forgetScopingTenant();

        return $this;
    }

    public function scopeBypassed(): bool
    {
        return $this->scopeBypass || ! $this->scopingTenantId();
    }

    public function bypassScope(): void
    {
        $this->scopeBypass = true;
    }

    public function resetScopeBypass(): void
    {
        $this->scopeBypass = false;
    }

    public function creatingBypassed(): bool
    {
        return $this->creatingBypass || ! $this->tenantId();
    }

    public function bypassCreating(): void
    {
        $this->creatingBypass = true;
    }

    public function resetCreatingBypass(): void
    {
        $this->creatingBypass = false;
    }

    public function forgetTenant(): void
    {
        $this->tenantIdWasResolved = false;
        $this->tenantId = null;

        unset($this->scopingTenantIdResolver);
        $this->forgetScopingTenant();
    }

    public function forgetScopingTenant(): void
    {
        $this->scopingTenantIdWasResolved = false;
        $this->scopingTenantId = null;
    }
}
