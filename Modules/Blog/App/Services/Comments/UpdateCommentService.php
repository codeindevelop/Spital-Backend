<?php

namespace Modules\Blog\App\Services\Comments;

use Modules\Blog\App\Models\PostComment;
use Modules\Blog\App\Repositories\Comments\UpdateCommentRepository;

class UpdateCommentService
{
    protected UpdateCommentRepository $commentRepository;

    public function __construct(UpdateCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Update a comment by its ID.
     *
     * @param  string  $id
     * @param  array  $data
     * @param  string  $userId
     * @return PostComment
     */
    public function execute(string $id, array $data, string $userId): PostComment
    {
        $commentData = array_filter([
            'content' => $data['content'] ?? null,
            'status' => $data['status'] ?? null,
            'updated_by' => $userId,
        ]);

        return $this->commentRepository->execute($id, $commentData);
    }
}
