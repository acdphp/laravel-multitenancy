<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;

/**
 * @property int $id
 * @property int $company_id
 * @property string $foo
 */
class SiteNoAutoAssign extends Site
{
    use BelongsToTenant;

    public $table = 'sites';

    public bool $autoAssignTenantId = false;

    protected $fillable = [
        'company_id',
        'foo',
    ];
}
