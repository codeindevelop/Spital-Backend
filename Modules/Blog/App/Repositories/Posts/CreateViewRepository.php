<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostView;

class CreateViewRepository
{
    /**
     * Create a view for a post.
     *
     * @param  array  $data
     * @return PostView
     */
    public function execute(array $data): PostView
    {
        return PostView::create($data);
    }
}
