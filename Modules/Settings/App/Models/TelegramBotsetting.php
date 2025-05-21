<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class TelegramBotsetting extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'bot_api_tokent',
        'active',

    ];
}
