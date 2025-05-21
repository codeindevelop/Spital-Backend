<?php

namespace Modules\User\App\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @method static where(string $string, mixed $code)
 * @method static create(array $array)
 */
class UserVerify extends Model
{
    use HasFactory,HasUuids;


    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class);
    }

    protected $fillable = [
        'user_id',
        'account_type',
        'shenasname_no',
        'mellicode',
        'email_verified_at',
        'mobile_verified_at',
        'email_verify_token',
        'mobile_verify_token',

        'verify_email',
        'verify_mobile_number',
        'passport_number',
        'passport_document',
        'shenasname_document',
        'mellicard_document',
        'mellicard_back_document',
        'atbaa_document',

        'verify_dateTime',
        'reject_dateTime',
        'reject_status',
        'status'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'email_verify_token',
        'mobile_verify_token',
    ];
}
