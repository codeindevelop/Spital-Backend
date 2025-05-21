<?php

namespace Modules\Training\App\Models\Skill;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $id)
 */
class SkillCategory extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'category_name',
        'description',
        'active',

    ];
}
