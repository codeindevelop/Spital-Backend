<?php

namespace Modules\Blog\App\Services\Comments;

use Illuminate\Support\Facades\Log;
use Modules\Blog\App\Models\PostComment;
use Modules\Blog\App\Repositories\Comments\CreateCommentRepository;
use Ramsey\Uuid\Uuid;

class CreateCommentService
{
    protected CreateCommentRepository $commentRepository;

    public function __construct(CreateCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Create a new comment for a post.
     *
     * @param  array  $data
     * @param  object  $user
     * @return PostComment
     * @throws \Exception
     */
    public function execute(array $data, object $user): PostComment
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
                'status' => $data['status'] ?? 'pending',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];

            return $this->commentRepository->execute($commentData);
        } catch (\Exception $e) {
            Log::error('Error creating comment: '.$e->getMessage(), [
                'data' => $data,
                'userId' => $user->id, // اصلاح: استفاده از $user->id به جای $userId
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
