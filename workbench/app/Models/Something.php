<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Something extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'foo',
    ];
}
