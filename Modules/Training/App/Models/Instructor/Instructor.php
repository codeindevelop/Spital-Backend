<?php

namespace Modules\Training\App\Models\Instructor;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Modules\Training\App\Models\Course\Course;
use Modules\User\App\Models\User;

/**
 * @method static where(string $string, mixed $instructor_slug)
 */
class Instructor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',

        'instructor_slug',


        'link_instagram',
        'link_facebook',
        'link_linkedin',
        'link_twitter',
        'link_github',
        'link_youtube',
        'link_website',
        'skils',
        'short_biography',
        'biography',
        'instructor_image',
    ];


    public function userData(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {

        return $this->hasMany(Course::class);
    }
}
