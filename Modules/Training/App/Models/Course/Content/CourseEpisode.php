<?php

namespace Modules\Training\App\Models\Course\Content;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class CourseEpisode extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'course_season_id',
        'episode_order_number',
        'episode_name',
        'episode_desc',
        'duration',
        'active',

    ];

    public function season(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CourseSeason::class);
    }

    public function videos(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CourseVideo::class);
    }
}
