<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $org_id
 * @property string $foo
 */
class SiteCustomKey extends Model
{
    use BelongsToTenant;

    public $table = 'custom_key_sites';

    protected string $tenantRefKey = 'org_id';

    protected $fillable = [
        'foo',
    ];
}
