<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $int)
 * @method static create(array $array)
 */
class SmsSetting extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'send_sms_company',
        'send_sms_server',
        'api_key',
        'send_sms_number',
        'receive_sms_number',
        'active',
    ];
}
