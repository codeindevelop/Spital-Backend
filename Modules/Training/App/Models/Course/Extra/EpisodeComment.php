<?php

namespace Modules\Training\App\Models\Course\Extra;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodeComment extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'episode_id',
        'commented_user',
        'comment_parent',
        'comment_author_IP',
        'comment_content',
        'like_number',
        'comment_approved',

        'active',

    ];
}
