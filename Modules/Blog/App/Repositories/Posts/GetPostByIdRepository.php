<?php

namespace Modules\Blog\App\Repositories\Posts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Models\Post;

class GetPostByIdRepository
{
    /**
     * Retrieve a post by its ID.
     *
     * @param  string  $id
     * @return Builder|array|Collection|Model
     */
    public function execute(string $id): Builder|array|Collection|Model
    {
        return Post::with(['author', 'category', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
    }
}
