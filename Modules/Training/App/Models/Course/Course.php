<?php

namespace Modules\Training\App\Models\Course;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Training\App\Models\Course\Content\CourseEpisode;
use Modules\Training\App\Models\Course\Content\CourseSeason;
use Modules\Training\App\Models\Instructor\Instructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed $id
 * @method static where(string $string, $id)
 * @method static findOrFail($id)
 */
class Course extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'category_id',
        'instructor_id',
        'course_title',
        'course_slug',
        'short_desc',
        'longdesc',
        'keywords',
        'course_image',
        'cover_image',
        'course_intro_video',
        'course_time',
        'course_type',
        'need_requirement',
        'is_special',
        'course_level',
        'status',
        'price',
        'active',
    ];


    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {

        return $this->belongsTo(CourseCategory::class, 'category_id');

    }

    public function seasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {

        return $this->hasMany(CourseSeason::class);

    }

    public function instructors(): \Illuminate\Database\Eloquent\Relations\HasOne
    {

        return $this->hasOne(Instructor::class);
    }


    public static function is_subscribed($course_id, $user_id)
    {
        $course = DB::table('courses')->where('id', $course_id)->first();
        return DB::table('course_students')
            ->where('course_id', $course->id)
            ->where('user_id', $user_id)
            ->first();
    }

    public function requirements()
    {
        // $course = CourceRequirement::where('course_id', $course_id)->first();
        // return DB::table('course_students')
        //     ->where('course_id', $course->id)
        //     ->where('user_id', $user_id)
        //     ->first();
        return $this->hasMany(CourseRequirement::class)->withTimestamps();
    }
}
