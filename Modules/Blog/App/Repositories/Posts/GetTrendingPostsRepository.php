<?php

namespace Modules\Blog\App\Repositories\Posts;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Models\TrendingPost;

class GetTrendingPostsRepository
{
    /**
     * Retrieve trending posts with pagination.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        return TrendingPost::with('post')->orderBy('views_count', 'desc')->paginate($perPage);
    }
}
