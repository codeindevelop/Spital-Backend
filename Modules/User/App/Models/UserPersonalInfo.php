<?php

namespace Modules\User\App\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserPersonalInfo extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'account_type',
        'user_name',
        'first_name',
        'last_name',
        'display_name',
        'gender',
        'profile_image',
        'cover_image',
        'father_name',
        'father_last_name',
        'mother_name',
        'mother_last_name',
        'short_bio',
        'long_bio',
        'marital_status',
        'identity_number',
        'national_id',
        'date_of_birth',
        'place_of_birth',
        'born_city',
        'live_city',
        'phone_number',
        'home_address',
        'social_security_number',
        'tax_id_number',
        'occupation',
        'job_title',
        'job_type',
        'job_location',
        'job_department',
        'job_experience_years',
        'job_skills',
        'job_industry',
        'job_company',
        'job_company_address',
        'job_company_phone',
        'university_name',
        'education_level',
        'education_field',
        'education_degree',
        'education_institution',
        'education_start_date',
        'education_end_date',
        'education_grade',
        'education_country',
        'education_city',
        'education_major',
        'education_minor',
        'education_certification',
        'education_start_year',
        'education_end_year',
        'education_description',
        'education_document',
        'education_document_url',
        'education_document_type',
        'education_document_number',
        'education_document_issue_date',
        'education_document_expiry_date',
        'education_document_issuer',
        'education_document_issuer_address',
        'education_document_issuer_phone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // اگر تاریخ‌ها به‌صورت رشته ذخیره می‌شن، نیازی به cast به datetime نیست
        // اما اگر بخوای به‌عنوان تاریخ مدیریتشون کنی، می‌تونی اضافه کنی:
        // 'date_of_birth' => 'date',
        // 'education_start_date' => 'date',
        // 'education_end_date' => 'date',
        // 'education_document_issue_date' => 'date',
        // 'education_document_expiry_date' => 'date',
    ];

    protected $hidden = [
        'user_id', // مخفی کردن user_id برای جلوگیری از افشا در پاسخ‌های JSON
        'identity_number', // اطلاعات حساس
        'national_id', // اطلاعات حساس
        'social_security_number', // اطلاعات حساس
        'tax_id_number', // اطلاعات حساس
        'phone_number', // اطلاعات حساس
        'home_address', // اطلاعات حساس
        'education_document_number', // اطلاعات حساس
    ];

    // روابط
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
