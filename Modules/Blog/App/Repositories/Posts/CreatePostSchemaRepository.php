<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Seo\App\Models\post\PostSchema;

class CreatePostSchemaRepository
{
    /**
     * Create schema data for a post.
     *
     * @param  string  $postId
     * @param  array  $data
     * @return PostSchema
     */
    public function execute(string $postId, array $data): PostSchema
    {
        return PostSchema::create(array_merge($data, ['post_id' => $postId]));
    }
}
