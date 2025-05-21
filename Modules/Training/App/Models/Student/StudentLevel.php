<?php

namespace Modules\Training\App\Models\Student;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $level_name)
 * @method static findOrFail($id)
 */
class StudentLevel extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'level_name',
        'level_image',
    ];
}
