<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthSetting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'two_factor_enabled',
        'two_factor_method',
        'password_reset_enabled',
        'password_reset_method',
        'email_verification_required',
        'sms_verification_required',
        'phone_verification_required',
        'user_profile_visibility_enabled',
        'user_profile_visibility',
        'user_profile_editing_enabled',
        'security_questions_enabled',
        'security_questions',
        'multi_factor_authentication_enabled',
        'mfa_method',
        'account_lockout_enabled',
        'account_lockout_threshold',
        'account_lockout_duration',
        'session_management_enabled',
        'session_management_method',
        'session_management_devices',
        'api_access_enabled',
        'api_access_token',
        'api_access_token_expiry',
        'ip_restriction_enabled',
        'allowed_ip_addresses',
        'geo_restriction_enabled',
        'geo_restriction_countries',
        'data_export_enabled',
        'data_export_method',
        'data_export_email',
        'data_export_file_path',
        'data_import_enabled',
        'data_import_method',
        'data_import_file_path',
        'data_backup_enabled',
        'data_backup_method',
        'data_backup_service',
        'data_backup_file_path',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'password_reset_enabled' => 'boolean',
        'email_verification_required' => 'boolean',
        'sms_verification_required' => 'boolean',
        'phone_verification_required' => 'boolean',
        'user_profile_visibility_enabled' => 'boolean',
        'user_profile_editing_enabled' => 'boolean',
        'security_questions_enabled' => 'boolean',
        'security_questions' => 'array',
        'multi_factor_authentication_enabled' => 'boolean',
        'account_lockout_enabled' => 'boolean',
        'session_management_enabled' => 'boolean',
        'api_access_enabled' => 'boolean',
        'ip_restriction_enabled' => 'boolean',
        'geo_restriction_enabled' => 'boolean',
        'data_export_enabled' => 'boolean',
        'data_import_enabled' => 'boolean',
        'data_backup_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'user_id', // مخفی کردن user_id برای جلوگیری از افشا در پاسخ‌های JSON
        'api_access_token', // مخفی کردن توکن API برای امنیت
        'security_questions', // مخفی کردن سوالات امنیتی برای حفظ حریم خصوصی
        'allowed_ip_addresses', // مخفی کردن IPها برای امنیت
        'data_export_file_path', // مخفی کردن مسیر فایل برای امنیت
        'data_import_file_path', // مخفی کردن مسیر فایل برای امنیت
        'data_backup_file_path', // مخفی کردن مسیر فایل برای امنیت
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
