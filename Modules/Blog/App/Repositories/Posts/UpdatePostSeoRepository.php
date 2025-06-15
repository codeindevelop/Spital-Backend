<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Seo\App\Models\post\PostSeo;

class UpdatePostSeoRepository
{
    /**
     * Update or create SEO data for a post.
     *
     * @param  string  $postId
     * @param  array  $data
     * @return PostSeo
     */
    public function execute(string $postId, array $data): PostSeo
    {
        $seo = PostSeo::where('post_id', $postId)->first();
        if ($seo) {
            $seo->update(array_filter($data));
        } else {
            $seo = PostSeo::create(array_merge($data, ['post_id' => $postId]));
        }
        return $seo;
    }
}
