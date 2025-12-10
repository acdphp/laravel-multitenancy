<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $email
 * @property string $password
 */
class User extends Authenticatable
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
