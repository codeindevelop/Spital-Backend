<?php

namespace Modules\Blog\App\Repositories\Categories;

use Modules\Blog\App\Models\PostCategory;

class DeleteCategoryRepository
{
    /**
     * Delete a category by its ID.
     *
     * @param  string  $id
     * @return void
     */
    public function execute(string $id): void
    {
        $category = PostCategory::findOrFail($id);
        $category->delete();
    }
}
