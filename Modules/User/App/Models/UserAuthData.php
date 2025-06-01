<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class UserAuthData extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'register_ip',
        'register_device',
        'register_browser',
        'register_location',
        'registered_at',
        'last_login_at',
        'last_login_method',
        'last_login_ip',
        'last_login_device',
        'last_login_browser',
        'last_login_location',
        'suspended_at',
        'banned_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'last_login_at' => 'datetime',
        'suspended_at' => 'datetime',
        'banned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'user_id', // مخفی کردن user_id برای جلوگیری از افشا در پاسخ‌های JSON
        'register_ip', // مخفی کردن IP برای حفظ حریم خصوصی
        'last_login_ip', // مخفی کردن IP برای حفظ حریم خصوصی
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
