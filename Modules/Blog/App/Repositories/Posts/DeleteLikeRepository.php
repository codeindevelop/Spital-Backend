<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostLike;

class DeleteLikeRepository
{
    /**
     * Delete a like by its ID.
     *
     * @param  string  $likeId
     * @return void
     */
    public function execute(string $likeId): void
    {
        PostLike::where('id', $likeId)->delete();
    }
}
