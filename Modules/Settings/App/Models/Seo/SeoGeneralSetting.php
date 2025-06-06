<?php

namespace Modules\Settings\App\Models\Seo;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\App\Models\User;

/**
 * @method static create(array $array)
 * @method static firstOrFail()
 * @property mixed $site_name
 */
class SeoGeneralSetting extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'seo_general_settings';

    protected $fillable = [
        'site_name',
        'site_alternative_name',
        'site_slogan',
        'og_image',
        'title_separator',

    ];

}
