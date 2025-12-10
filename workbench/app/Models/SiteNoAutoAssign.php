<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;

class SiteNoAutoAssign extends Site
{
    use BelongsToTenant;

    public $table = 'sites';

    public bool $autoAssignTenantId = false;

    protected $fillable = [
        'foo',
    ];
}
