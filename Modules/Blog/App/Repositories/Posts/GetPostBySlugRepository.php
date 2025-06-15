<?php

namespace Modules\Blog\App\Repositories\Posts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Models\Post;

class GetPostBySlugRepository
{
    /**
     * Retrieve a post by its slug.
     *
     * @param  string  $slug
     * @return Builder|Model
     */
    public function execute(string $slug): Builder|Model
    {
        return Post::with(['author', 'category', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
