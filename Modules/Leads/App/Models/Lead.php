<?php

namespace Modules\Leads\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;


/**
 * @property mixed $send_welcome_sms
 * @property mixed $send_welcome_email
 * @property mixed $email
 * @property mixed $first_name
 * @property mixed $mobile_number
 */
class Lead extends Model
{
    use HasUuids;

    protected $table = 'leads';


    protected $fillable = [

        'staff_id',
        'source_id',
        'status_id',
        'first_name',
        'last_name',
        'email',
        'country',
        'city',
        'address',
        'zip_code',
        'website',
        'phone',
        'mobile_number',
        'company_name',
        'description',
        'tags',
        'is_public',
        'send_welcome_sms',
        'send_welcome_email',
        'active'
    ];


}
