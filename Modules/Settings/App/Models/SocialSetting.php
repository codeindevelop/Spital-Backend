<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 */
class SocialSetting extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'instagram',
        'telegram',
        'whatsapp',
        'whatsapp_business',
        'facebook_user',
        'facebook_page',
        'twitter',
        'skype',
        'youtube',
        'aparat',
        'tiktok',
        'pintrest',
        'linkdin',
        'dribbble',
        'snapchat',
    ];
}
