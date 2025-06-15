<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostBookmark;

class DeleteBookmarkRepository
{
    /**
     * Delete a bookmark by its ID.
     *
     * @param  string  $bookmarkId
     * @return void
     */
    public function execute(string $bookmarkId): void
    {
        PostBookmark::where('id', $bookmarkId)->delete();
    }
}
