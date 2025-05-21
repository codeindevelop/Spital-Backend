<?php

namespace Modules\Training\App\Models\Skill;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $skill_name)
 */
class Skill extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'category_id',
        'skill_name',
        'requirement_skills',
        'job_level',
        'description',
        'day_need_to_learn',
        'minimum_salary',
        'migration_level',
        'image',
        'cover_image',
        'software_requirement',
        'used_platform',
        'active',



    ];
}
