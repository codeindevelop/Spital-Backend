<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJoinPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'active',
    ];
}
