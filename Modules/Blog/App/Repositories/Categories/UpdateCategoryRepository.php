<?php

namespace Modules\Blog\App\Repositories\Categories;

use Modules\Blog\App\Models\PostCategory;

class UpdateCategoryRepository
{
    /**
     * Update a category by its ID.
     *
     * @param  string  $id
     * @param  array  $data
     * @return PostCategory
     */
    public function execute(string $id, array $data): PostCategory
    {
        $category = PostCategory::findOrFail($id);
        $category->update(array_filter($data));
        return $category;
    }
}
