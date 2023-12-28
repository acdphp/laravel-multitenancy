<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
