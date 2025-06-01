<?php

namespace Modules\Auth\Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Modules\RolePermission\App\Models\Role;
use Modules\User\App\Models\User;

use Illuminate\Support\Str;
use Modules\User\App\Models\UserAuthData;
use Modules\User\App\Models\UserAuthSetting;
use Modules\User\App\Models\UserPersonalInfo;
use Modules\User\App\Models\UserVerify;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ایجاد کاربر ادمین
        $admin = User::create([
            'id' => (string) Str::uuid(),
            'email' => env('ADMIN_EMAIL', 'admin@abrebase.com'),
            'mobile_number' => env('ADMIN_MOBILE', '12345678'),
            'password' => bcrypt(env('ADMIN_PASSWORD', 'adminadmin')),
            'last_password' => null,
            'last_password_change' => null,
            'active' => true,
        ]);

        // ایجاد اطلاعات شخصی
        UserPersonalInfo::create([
            'id' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'account_type' => 'individual',
            'user_name' => env('ADMIN_USER_NAME', 'admin'),
            'first_name' => env('ADMIN_FIRST_NAME', 'hadi'),
            'last_name' => env('ADMIN_LAST_NAME', 'mo'),
            'display_name' => env('ADMIN_FIRST_NAME', 'hadi').' '.env('ADMIN_LAST_NAME', 'mo'),
            'gender' => env('ADMIN_GENDER', 'male'),
            'profile_image' => env('ADMIN_PROFILE_IMAGE', 'https://example.com/admin_profile.jpg'),
            'cover_image' => null,
            'father_name' => env('ADMIN_FATHER_NAME', 'علی'),
            'father_last_name' => env('ADMIN_FATHER_LAST_NAME', 'موسوی'),
            'mother_name' => env('ADMIN_MOTHER_NAME', 'فاطمه'),
            'mother_last_name' => env('ADMIN_MOTHER_LAST_NAME', 'احمدی'),
            'short_bio' => env('ADMIN_SHORT_BIO', 'ادمین ارشد سیستم ابریکد'),
            'long_bio' => null,
            'marital_status' => env('ADMIN_MARITAL_STATUS', 'married'),
            'identity_number' => null,
            'national_id' => env('ADMIN_NATIONAL_ID', '9876543210'),
            'date_of_birth' => env('ADMIN_DATE_OF_BIRTH', '1985-05-15'),
            'place_of_birth' => 'تهران, ایران',
            'born_city' => 'تهران',
            'live_city' => 'تهران',
            'phone_number' => env('ADMIN_PHONE_NUMBER', '12345678'),
            'home_address' => env('ADMIN_HOME_ADDRESS', 'تهران، خیابان اصلی، پلاک 123'),
            'social_security_number' => null,
            'tax_id_number' => null,
            'occupation' => 'مدیر سیستم',
            'job_title' => 'ادمین ارشد',
            'job_type' => 'full-time',
            'job_location' => 'تهران, ایران',
            'job_department' => 'مدیریت',
            'job_experience_years' => '10 سال',
            'job_skills' => 'مدیریت سیستم، لاراول، Next.js',
            'job_industry' => 'فناوری',
            'job_company' => 'ابریکد',
            'job_company_address' => 'تهران، خیابان فرعی، پلاک 456',
            'job_company_phone' => '+982112345678',
            'university_name' => null,
            'education_level' => null,
            'education_field' => null,
            'education_degree' => null,
            'education_institution' => null,
            'education_start_date' => null,
            'education_end_date' => null,
            'education_grade' => null,
            'education_country' => null,
            'education_city' => null,
            'education_major' => null,
            'education_minor' => null,
            'education_certification' => null,
            'education_start_year' => null,
            'education_end_year' => null,
            'education_description' => null,
            'education_document' => null,
            'education_document_url' => null,
            'education_document_type' => null,
            'education_document_number' => null,
            'education_document_issue_date' => null,
            'education_document_expiry_date' => null,
            'education_document_issuer' => null,
            'education_document_issuer_address' => null,
            'education_document_issuer_phone' => null,
        ]);

        // ایجاد داده‌های احراز هویت
        UserAuthData::create([
            'id' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'register_ip' => '192.168.1.1',
            'register_device' => 'desktop',
            'register_browser' => 'Chrome',
            'register_location' => 'تهران, ایران',
            'registered_at' => now(),
            'last_login_at' => null,
            'last_login_method' => null,
            'last_login_ip' => null,
            'last_login_device' => null,
            'last_login_browser' => null,
            'last_login_location' => null,
            'suspended_at' => null,
            'banned_at' => null,
        ]);


        // ایجاد تنظیمات احراز هویت
        UserAuthSetting::create([
            'id' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'two_factor_enabled' => true,
            'two_factor_method' => 'email',
            'password_reset_enabled' => true,
            'password_reset_method' => 'email',
            'email_verification_required' => true,
            'sms_verification_required' => false,
            'phone_verification_required' => false,
            'user_profile_visibility_enabled' => true,
            'user_profile_visibility' => 'private',
            'user_profile_editing_enabled' => true,
            'security_questions_enabled' => false,
            'security_questions' => null,
            'multi_factor_authentication_enabled' => false,
            'mfa_method' => 'totp',
            'account_lockout_enabled' => true,
            'account_lockout_threshold' => 5,
            'account_lockout_duration' => 15,
            'session_management_enabled' => true,
            'session_management_method' => 'browser',
            'session_management_devices' => json_encode([['device' => 'Chrome on Windows', 'last_used' => now()]]),
            'api_access_enabled' => true,
            'api_access_token' => Str::random(64),
            'api_access_token_expiry' => now()->addYear()->toDateTimeString(),
            'ip_restriction_enabled' => false,
            'allowed_ip_addresses' => null,
            'geo_restriction_enabled' => false,
            'geo_restriction_countries' => null,
            'data_export_enabled' => true,
            'data_export_method' => 'email',
            'data_export_email' => env('ADMIN_EMAIL', 'admin@abrebase.com'),
            'data_export_file_path' => null,
            'data_import_enabled' => false,
            'data_import_method' => 'upload',
            'data_import_file_path' => null,
            'data_backup_enabled' => true,
            'data_backup_method' => 'cloud',
            'data_backup_service' => 'Google Drive',
            'data_backup_file_path' => null,
        ]);


        // ایجاد اطلاعات تأیید هویت (به‌صورت تأییدشده)
        UserVerify::create([
            'id' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'passport_number' => env('ADMIN_PASSPORT_NUMBER', 'A12345678'),
            'shenasname_number' => env('ADMIN_SHENASNAME_NUMBER', '1234567890'),
            'mellicard_number' => env('ADMIN_MELLICARD_NUMBER', '9876543210'),
            'mellicard_back_number' => null,
            'atbaa_number' => null,
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
            'email_verify_token' => null,
            'mobile_verify_token' => null,
            'verify_email' => true,
            'verify_mobile_number' => true,
            'passport_document' => 'https://example.com/admin_passport.pdf',
            'shenasname_document' => 'https://example.com/admin_shenasname.pdf',
            'mellicard_document' => 'https://example.com/admin_mellicard.pdf',
            'mellicard_back_document' => null,
            'atbaa_document' => null,
            'verify_dateTime' => now(),
            'reject_dateTime' => null,
            'reject_status' => null,
            'status' => 'verified',
        ]);

        // تخصیص نقش ادمین
        $role = Role::findByName('admin', 'api');
        $admin->assignRole($role);

        // لاگ فعالیت
//        activity()
//            ->causedBy($admin)
//            ->event('create-user')
//            ->withProperties(['user' => $admin])
//            ->log('create admin');
    }
}
