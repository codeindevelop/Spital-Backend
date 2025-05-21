<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 */
class EmailSetting extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'send_email_company',
        'send_email_host',
        'email_protocol',
        'email_protocol',
        'email_encryption',
        'send_email_name',
        'send_email_url',
        'smtp_host',
        'send_email_port',
        'smtp_username',
        'smtp_password',



    ];
}
