<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalizationSetting extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'country_id',
        'language_id',
        'timezone_id',
    ];
}
