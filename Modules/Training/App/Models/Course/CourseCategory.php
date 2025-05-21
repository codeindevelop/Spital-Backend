<?php

namespace Modules\Training\App\Models\Course;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static where(string $string, mixed $category_name)
 */
class CourseCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'category_name',
        'slug',
        'category_image',
        'description',
        'active',

    ];
}
