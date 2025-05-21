<?php

namespace Modules\Training\App\Models\Course;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseUserProgress extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_id',
        'user_id',
        'progress_number',

    ];
}
