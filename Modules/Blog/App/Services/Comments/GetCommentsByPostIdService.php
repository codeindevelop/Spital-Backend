<?php

namespace Modules\Blog\App\Services\Comments;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Repositories\Comments\GetCommentsByPostIdRepository;

class GetCommentsByPostIdService
{
    protected GetCommentsByPostIdRepository $commentRepository;

    public function __construct(GetCommentsByPostIdRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Retrieve comments for a post by its ID with pagination.
     *
     * @param  string  $postId
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function execute(string $postId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->commentRepository->execute($postId, $perPage);
    }
}
