<?php

namespace Modules\Seo\App\Models\Setting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class SeoRepresentationSetting extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $table = 'seo_representation_settings';

    protected $fillable = [
        'site_type',
        'company_name',
        'company_alternative_name',
        'company_logo',
        'company_description',
        'company_email',
        'company_phone',
        'company_legal_name',
        'company_founded_date',
        'company_employee_count',
        'company_branch_count',
        'company_address',
        'company_ceo',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_telegram',
        'social_tiktok',
        'social_snapchat',
        'social_threads',
        'social_github',
        'social_linkedin',
        'social_pinterest',
        'social_wikipedia',
    ];

    protected $casts = [
        'company_founded_date' => 'date',
        'company_employee_count' => 'integer',
        'company_branch_count' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // لاگ تمام فیلدهای fillable
            ->logOnlyDirty() // فقط تغییرات رو لاگ کن
            ->dontSubmitEmptyLogs(); // لاگ خالی نفرست
    }
}
