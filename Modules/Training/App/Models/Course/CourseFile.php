<?php

namespace Modules\Training\App\Models\Course;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @method static findOrFail($id)
 */
class CourseFile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_id',
        'uploader_id',
        'file_name',
        'file_title',
        'file_type',
        'file_url',
        'file_size',
        'duration',
        'file_tag',
        'active',


    ];
}
