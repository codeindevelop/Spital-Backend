<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\Post;

class GetPostByShortLinkRepository
{
    /**
     * Retrieve a post by its short link.
     *
     * @param  string  $shortLink
     * @return Post|null
     */
    public function execute(string $shortLink): ?Post
    {
        return Post::where('short_link', $shortLink)->first();
    }
}
