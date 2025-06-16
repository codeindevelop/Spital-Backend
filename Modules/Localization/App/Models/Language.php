<?php

namespace Modules\Localization\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @method static create(string[] $language)
 * @method static where(string $string, string $string1)
 */
class Language extends Model
{
    use HasFactory,HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
    ];


}
