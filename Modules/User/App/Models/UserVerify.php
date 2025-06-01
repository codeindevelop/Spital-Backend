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
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'passport_number',
        'shenasname_number',
        'mellicard_number',
        'mellicard_back_number',
        'atbaa_number',
        'email_verified_at',
        'mobile_verified_at',
        'email_verify_token',
        'mobile_verify_token',
        'verify_email',
        'verify_mobile_number',
        'passport_document',
        'shenasname_document',
        'mellicard_document',
        'mellicard_back_document',
        'atbaa_document',
        'verify_dateTime',
        'reject_dateTime',
        'reject_status',
        'status',
    ];

    protected $casts = [
        'verify_email' => 'boolean',
        'verify_mobile_number' => 'boolean',
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
        'verify_dateTime' => 'datetime',
        'reject_dateTime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'user_id', // مخفی کردن user_id برای جلوگیری از افشا در پاسخ‌های JSON
        'passport_number', // اطلاعات حساس
        'shenasname_number', // اطلاعات حساس
        'mellicard_number', // اطلاعات حساس
        'mellicard_back_number', // اطلاعات حساس
        'atbaa_number', // اطلاعات حساس
        'email_verify_token', // اطلاعات حساس
        'mobile_verify_token', // اطلاعات حساس
        'passport_document', // اطلاعات حساس
        'shenasname_document', // اطلاعات حساس
        'mellicard_document', // اطلاعات حساس
        'mellicard_back_document', // اطلاعات حساس
        'atbaa_document', // اطلاعات حساس
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
