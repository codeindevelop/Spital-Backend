<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class GeneralSetting extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'portal_name',
        'portal_desc',
        'time_zone',
        'maintenance_mode',
        'signup_type',
        'user_panel_url',

    ];
}
