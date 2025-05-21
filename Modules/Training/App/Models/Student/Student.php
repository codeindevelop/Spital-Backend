<?php

namespace Modules\Training\App\Models\Student;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Auth\App\Models\User;

/**
 * @method static where(string $string, mixed $user_id)
 * @method static findOrFail(mixed $student_id)
 */
class Student extends Model
{
    use HasFactory, SoftDeletes, HasUuids;


    protected $fillable = [
        'user_id',
        'student_group_id',
        'level_id',
        'status',
        'profile_img',
        'cover_img',
        'about_student',
        'company_name',
        'location_name',
        'instagram_id',
        'x_id',
        'github_id',
        'gitlab_id',
        'bitbucket_id',
        'website_url',

        'register_datetime',
        'canceling_datetime',
        'job_name',
        'description',
        'active',
        'referer_code',
        'referer_name',
        'referer_mobile_number',
        'referer_email',
    ];


    // Get student user info
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class);
    }
}
