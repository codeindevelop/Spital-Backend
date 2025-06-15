<?php

namespace Modules\Blog\App\Repositories\Comments;

use Modules\Blog\App\Models\PostComment;

class UpdateCommentRepository
{
    /**
     * Update a comment by its ID.
     *
     * @param  string  $id
     * @param  array  $data
     * @return PostComment
     */
    public function execute(string $id, array $data): PostComment
    {
        $comment = PostComment::findOrFail($id);
        $comment->update(array_filter($data));
        return $comment;
    }
}
