<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\Post;

class DeletePostRepository
{
    /**
     * Delete a post by its ID.
     *
     * @param  string  $id
     * @return void
     */
    public function execute(string $id): void
    {
        $post = Post::findOrFail($id);
        $post->delete();
    }
}
