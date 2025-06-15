<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostLike;

class CreateLikeRepository
{
    /**
     * Create a like for a post.
     *
     * @param  array  $data
     * @return PostLike
     */
    public function execute(array $data): PostLike
    {
        return PostLike::create($data);
    }
}
