<?php

namespace Modules\Training\App\Models\Path;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class TrainingPath extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'path_name',
        'path_target_tech',
        'short_desc',
        'long_desc',
        'active',
    ];
}
