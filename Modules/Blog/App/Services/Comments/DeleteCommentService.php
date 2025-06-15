<?php

namespace Modules\Blog\App\Services\Comments;

use Modules\Blog\App\Repositories\Comments\DeleteCommentRepository;

class DeleteCommentService
{
    protected DeleteCommentRepository $commentRepository;

    public function __construct(DeleteCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Delete a comment by its ID.
     *
     * @param  string  $id
     * @return void
     */
    public function execute(string $id): void
    {
        $this->commentRepository->execute($id);
    }
}
