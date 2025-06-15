<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Seo\App\Models\post\PostSeo;

class CreatePostSeoRepository
{
    /**
     * Create SEO data for a post.
     *
     * @param  string  $postId
     * @param  array  $data
     * @return PostSeo
     */
    public function execute(string $postId, array $data): PostSeo
    {
        return PostSeo::create(array_merge($data, ['post_id' => $postId]));
    }
}
