<?php

namespace Modules\Training\App\Models\Student;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $group_name)
 * @method static findOrFail($id)
 */
class StudentGroup extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'group_name',
        'description',
        'active',
    ];
}
