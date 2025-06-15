<?php

namespace Modules\Blog\App\Repositories;


use Modules\Blog\App\Models\PostComment;

class PostCommentRepository
{
    public function getCommentsByPostId(
        string $postId,
        int $perPage
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        return PostComment::with(['user', 'children'])
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    public function createComment(array $data): PostComment
    {
        return PostComment::create($data);
    }

    public function updateComment(string $id, array $data): PostComment
    {
        $comment = PostComment::findOrFail($id);
        $comment->update(array_filter($data));
        return $comment;
    }

    public function deleteComment(string $id): void
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();
    }
}
