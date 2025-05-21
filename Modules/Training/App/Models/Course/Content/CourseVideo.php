<?php

namespace Modules\Training\App\Models\Course\Content;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @method static findOrFail($id)
 */
class CourseVideo extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'course_id',
        'course_season_id',
        'course_episode_id',
        'video_name',
        'video_desc',
        'video_link',
        'video_format',
        'video_size',
        'duration',
        'active',

    ];
}
