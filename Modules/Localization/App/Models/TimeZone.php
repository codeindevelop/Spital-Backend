<?php

namespace Modules\Localization\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;



/**
 * @method static create(string[] $language)
 * @method static where(string $string, string $string1)
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
