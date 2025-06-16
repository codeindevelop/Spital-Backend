<?php

namespace Modules\Settings\App\Models\System;

use Illuminate\Database\Eloquent\Model;

//use Modules\Localization\App\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Localization\App\Models\countries\Country;
use Modules\Localization\App\Models\Language;
use Modules\Localization\App\Models\TimeZone;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(null[] $array, array $data)
 * @method static first()
 */
class GeneralSetting extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'timezone_id',
        'language_id',
        'country_id',
        'site_name',
        'site_desc',
        'maintenance_mode',
        'user_panel_url',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];

    /**
     * Relationship with TimeZone
     */
    public function timezone(): BelongsTo
    {
        return $this->belongsTo(TimeZone::class, 'timezone_id', 'id');
    }

    /**
     * Relationship with Language
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    /**
     * Relationship with Country
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
