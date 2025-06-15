<?php

namespace Modules\Blog\App\Repositories\Comments;

use Modules\Blog\App\Models\PostComment;

class DeleteCommentRepository
{
    /**
     * Delete a comment by its ID.
     *
     * @param  string  $id
     * @return void
     */
    public function execute(string $id): void
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();
    }
}
