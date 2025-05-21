<?php

namespace Modules\Training\App\Models\Course\Extra;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCertification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_id',
        'certificate_id',
        'certificate_text',
        'certificate_desc',
        'active',

    ];
}
