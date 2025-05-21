<?php

namespace Modules\Training\App\Models\Course\Extra;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_id',
        'review_submit_user_id',
        'review_text',
        'review_answer',
        'review_rate_number',

        'active',

    ];
}
