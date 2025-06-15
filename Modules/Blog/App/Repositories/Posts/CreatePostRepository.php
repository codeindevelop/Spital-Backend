<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\Post;

class CreatePostRepository
{
    /**
     * Create a new post.
     *
     * @param  array  $data
     * @return Post
     */
    public function execute(array $data): Post
    {
        return Post::create($data);
    }
}
