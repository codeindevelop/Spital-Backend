<?php

namespace Modules\Training\App\Models\Course\Content;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @method static findOrFail($id)
 */
class CourseSeason extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'course_id',
        'season_order_number',
        'season_name',
        'season_desc',
        'duration',
        'active',

    ];

    public function episode()
    {

        return $this->hasMany(CourseEpisode::class);
    }


}
