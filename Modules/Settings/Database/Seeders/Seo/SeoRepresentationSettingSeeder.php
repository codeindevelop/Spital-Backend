<?php

namespace Modules\Settings\Database\Seeders\Seo;

use Illuminate\Database\Seeder;
use Modules\Seo\App\Models\Setting\SeoRepresentationSetting;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\Models\Activity;

class SeoRepresentationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // چک می‌کنیم که جدول خالی باشه تا از ایجاد رکورد تکراری جلوگیری کنیم
        if (SeoRepresentationSetting::count() === 0) {
            $setting = SeoRepresentationSetting::create([
                'id' => Uuid::uuid4()->toString(),
                'site_type' => 'company',
                'company_name' => 'ابریکُد',
                'company_alternative_name' => 'Abrecode Studio',
                'company_logo' => null,
                'company_description' => 'استودیو برنامه‌نویسی ابریکُد، ارائه‌دهنده راه‌حل‌های تخصصی توسعه نرم‌افزار و آموزش برنامه‌نویسی',
                'company_email' => 'info@abrecode.com',
                'company_phone' => '021-12345678',
                'company_legal_name' => 'شرکت فناوری اطلاعات ابریکُد',
                'company_founded_date' => '2020-01-01',
                'company_employee_count' => 50,
                'company_branch_count' => 2,
                'company_address' => 'تهران، خیابان اصلی، پلاک ۱۲۳',
                'company_ceo' => 'هادی موسوی',
                'social_facebook' => 'https://facebook.com/abrecode',
                'social_twitter' => 'https://twitter.com/abrecode_fa',
                'social_instagram' => 'https://instagram.com/abrecode',
                'social_telegram' => 'https://t.me/abrecode',
                'social_tiktok' => null,
                'social_snapchat' => null,
                'social_threads' => null,
                'social_github' => 'https://github.com/abrecode',
                'social_linkedin' => 'https://linkedin.com/company/abrecode',
                'social_pinterest' => null,
                'social_wikipedia' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Activity::create([
                'log_name' => 'seo_representation_settings',
                'description' => 'ایجاد رکورد اولیه تنظیمات نمایندگی SEO برای شرکت ابریکُد',
                'subject_type' => SeoRepresentationSetting::class,
                'subject_id' => $setting->id,
                'causer_id' => null, // بدون کاربر، چون از Seeder اجرا می‌شه
                'causer_type' => null,
                'properties' => ['attributes' => $setting->toArray()],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
