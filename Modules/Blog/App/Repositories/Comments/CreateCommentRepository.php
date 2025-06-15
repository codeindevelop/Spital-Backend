<?php

namespace Modules\Blog\App\Repositories\Comments;

use Modules\Blog\App\Models\PostComment;

class CreateCommentRepository
{
    /**
     * Create a new comment.
     *
     * @param  array  $data
     * @return PostComment
     */
    public function execute(array $data): PostComment
    {
        return PostComment::create($data);
    }
}
