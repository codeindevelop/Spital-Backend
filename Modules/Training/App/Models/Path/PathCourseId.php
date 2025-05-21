<?php

namespace Modules\Training\App\Models\Path;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathCourseId extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'path_id',
        'course_id',
        'active',
    ];
}
