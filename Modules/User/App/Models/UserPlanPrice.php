<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlanPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'du_price',
        'total_price',
        'du_monthly_price',
        'monthly_price',
        'can_pay_parttime',
        'part_pay_number',
        'part_pay_price',
        'note',
        'active',
    ];
}
