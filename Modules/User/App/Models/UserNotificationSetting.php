<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationSetting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'login_email',
        'login_sms',
        'register_email',
        'logout_email',
        'logout_sms',
        'change_password_email',
        'change_password_sms',
        'change_email_email',
        'change_email_sms',
        'change_mobile_email',
        'change_mobile_sms',
        'change_profile_image_email',
        'change_profile_image_sms',
    ];

    protected $casts = [
        'login_email' => 'boolean',
        'login_sms' => 'boolean',
        'register_email' => 'boolean',
        'logout_email' => 'boolean',
        'logout_sms' => 'boolean',
        'change_password_email' => 'boolean',
        'change_password_sms' => 'boolean',
        'change_email_email' => 'boolean',
        'change_email_sms' => 'boolean',
        'change_mobile_email' => 'boolean',
        'change_mobile_sms' => 'boolean',
        'change_profile_image_email' => 'boolean',
        'change_profile_image_sms' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'user_id', // مخفی کردن user_id برای جلوگیری از افشا در پاسخ‌های JSON
    ];

    // روابط
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
