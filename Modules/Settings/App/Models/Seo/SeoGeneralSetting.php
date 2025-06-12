<?php

namespace Modules\Settings\App\Models\Seo;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static firstOrFail()
 * @method static create(string[] $array)
 * @property mixed $og_image
 * @property mixed $site_name
 * @property mixed $site_slogan
 * @property mixed $site_alternative_name
 * @property mixed $title_separator
 */
class SeoGeneralSetting extends Model
{
    use LogsActivity;

    protected $table = 'seo_general_settings';

    protected $fillable = [
        'site_name',
        'site_alternative_name',
        'site_slogan',
        'og_image',
        'title_separator',
        'updated_by',
    ];

    protected $casts = [
        'site_name' => 'string',
        'site_alternative_name' => 'string',
        'site_slogan' => 'string',
        'og_image' => 'string',
        'title_separator' => 'string',
        'updated_by' => 'string',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // لاگ تمام فیلدهای fillable
            ->logOnlyDirty() // فقط تغییرات رو لاگ کن
            ->dontSubmitEmptyLogs(); // لاگ خالی نفرست
    }
}
