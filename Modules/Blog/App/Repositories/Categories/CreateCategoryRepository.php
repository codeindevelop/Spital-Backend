<?php

namespace Modules\Blog\App\Repositories\Categories;

use Modules\Blog\App\Models\PostCategory;

class CreateCategoryRepository
{
    /**
     * Create a new category.
     *
     * @param  array  $data
     * @return PostCategory
     */
    public function execute(array $data): PostCategory
    {
        return PostCategory::create($data);
    }
}
