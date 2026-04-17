<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomPermission extends SpatiePermission
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'guard_name',
        'permission_group_id',
        'description'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }
}
