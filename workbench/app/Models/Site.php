<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $company_id
 * @property string $foo
 */
class Site extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'company_id',
        'foo',
    ];
}
