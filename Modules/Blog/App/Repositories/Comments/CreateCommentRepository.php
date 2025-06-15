<?php

namespace Modules\Blog\App\Repositories\Comments;

use Modules\Blog\App\Models\PostComment;

class CreateCommentRepository
{
    public function execute(array $data): PostComment
    {
        return PostComment::create($data);
    }
}
