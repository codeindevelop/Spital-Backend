<?php

namespace Modules\Blog\App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Modules\Blog\App\Models\PostComment;
use Modules\Blog\App\Repositories\PostCommentRepository;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Log;

class PostCommentService
{
    protected PostCommentRepository $commentRepository;

    public function __construct(PostCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getCommentsByPostId(
        string $postId,
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->commentRepository->getCommentsByPostId($postId, $perPage);
    }

    public function createComment(array $data, $user): \Modules\Settings\App\Models\Blog\PostComment
    {
        try {
            $commentData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $data['post_id'],

                'parent_id' => $data['parent_id'] ?? null,
                'author_name' => $data['author_name'] ?? null,
                'author_email' => $data['author_email'] ?? null,
                'author_url' => $data['author_url'] ?? null,
                'author_ip' => request()->ip(),
                'content' => $data['content'],
                'status' => $data['status'] ?? 'pending', // استفاده از status درخواست یا پیش‌فرض pending
                'created_by' => $user->id, // مستقیم از $user->id
                'updated_by' => $user->id, // مستقیم از $user->id
            ];

            return $this->commentRepository->createComment($commentData);
        } catch (\Exception $e) {
            Log::error('Error creating comment: '.$e->getMessage(), [
                'data' => $data,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function updateComment(string $id, array $data, string $userId): PostComment
    {
        $commentData = array_filter([
            'content' => $data['content'] ?? null,
            'status' => $data['status'] ?? null,
            'updated_by' => $userId,
        ]);

        return $this->commentRepository->updateComment($id, $commentData);
    }

    public function deleteComment(string $id): void
    {
        $this->commentRepository->deleteComment($id);
    }
}
