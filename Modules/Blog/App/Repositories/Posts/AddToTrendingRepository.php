<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\TrendingPost;
use Ramsey\Uuid\Uuid;

class AddToTrendingRepository
{
    /**
     * Add a post to trending with views count.
     *
     * @param  string  $postId
     * @param  int  $viewsCount
     * @return void
     */
    public function execute(string $postId, int $viewsCount): void
    {
        TrendingPost::updateOrCreate(
            ['post_id' => $postId],
            [
                'id' => Uuid::uuid4()->toString(),
                'views_count' => $viewsCount,
                'trending_at' => now(),
            ]
        );
    }
}
