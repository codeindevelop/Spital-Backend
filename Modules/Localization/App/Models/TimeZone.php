<?php

namespace Modules\Localization\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;



/**
 * @method static create(string[] $language)
 */
class TimeZone extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'label',
        'value',
        'offset',
    ];


}
