<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\Post;

class UpdatePostRepository
{
    /**
     * Update a post by its ID.
     *
     * @param  string  $id
     * @param  array  $data
     * @return Post
     */
    public function execute(string $id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update(array_filter($data));
        return $post;
    }
}
