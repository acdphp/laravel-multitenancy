<?php

namespace Workbench\App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'site_id',
    ];

    protected string $scopeTenancyFromRelation = 'site';

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
