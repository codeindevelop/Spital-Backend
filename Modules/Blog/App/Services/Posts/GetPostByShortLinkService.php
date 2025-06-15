<?php

namespace Modules\Blog\App\Services\Posts;

use Modules\Blog\App\Models\Post;
use Modules\Blog\App\Repositories\Posts\GetPostByShortLinkRepository;

class GetPostByShortLinkService
{
    protected GetPostByShortLinkRepository $postRepository;

    public function __construct(GetPostByShortLinkRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Retrieve a post by its short link.
     *
     * @param  string  $shortLink
     * @return Post|null
     */
    public function execute(string $shortLink): ?Post
    {
        return $this->postRepository->execute($shortLink);
    }
}
