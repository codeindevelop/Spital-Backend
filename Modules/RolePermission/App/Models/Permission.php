<?php

namespace Modules\RolePermission\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @method static firstOrCreate(array $array)
 */
class Permission extends SpatiePermission
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'uuid';
}
