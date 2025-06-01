<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'plan_name',
        'description',
        'note',
        'is_free',

        'active',
    ];
}
