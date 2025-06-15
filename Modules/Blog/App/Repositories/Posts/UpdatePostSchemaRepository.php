<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Seo\App\Models\post\PostSchema;

class UpdatePostSchemaRepository
{
    /**
     * Update or create schema data for a post.
     *
     * @param  string  $postId
     * @param  array  $data
     * @return PostSchema
     */
    public function execute(string $postId, array $data): PostSchema
    {
        $schema = PostSchema::where('post_id', $postId)->first();
        if ($schema) {
            $schema->update(array_filter($data));
        } else {
            $schema = PostSchema::create(array_merge($data, ['post_id' => $postId]));
        }
        return $schema;
    }
}
