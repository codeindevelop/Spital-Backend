<?php

namespace Modules\Blog\App\Repositories\Comments;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Models\PostComment;

class GetCommentsByPostIdRepository
{
    /**
     * Retrieve comments for a post by its ID with pagination.
     *
     * @param  string  $postId
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function execute(string $postId, int $perPage): LengthAwarePaginator
    {
        return PostComment::with(['user', 'children'])
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }
}
