<?php

namespace Modules\Localization\App\Models\countries;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Country (Shahr)
 * @method static where(string $string, string $string1)
 */
class Country extends Model
{
    use HasUuids;

    protected $table = 'countries';

    protected $fillable = [

        'id'
    ];
}
