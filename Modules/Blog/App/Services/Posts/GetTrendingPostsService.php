<?php

namespace Modules\Blog\App\Services\Posts;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Repositories\Posts\GetTrendingPostsRepository;


class GetTrendingPostsService
{
    protected GetTrendingPostsRepository $getTrendingPostsRepository;

    public function __construct(GetTrendingPostsRepository $getTrendingPostsRepository)
    {
        $this->getTrendingPostsRepository = $getTrendingPostsRepository;
    }

    /**
     * Retrieve trending posts based on views count.
     *
     * @param  int  $limit
     * @return LengthAwarePaginator
     */
    public function execute(int $limit): LengthAwarePaginator
    {
        return $this->getTrendingPostsRepository->execute($limit);
    }
}
