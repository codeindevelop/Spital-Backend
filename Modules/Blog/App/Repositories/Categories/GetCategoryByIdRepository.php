<?php

namespace Modules\Blog\App\Repositories\Categories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Models\PostCategory;

class GetCategoryByIdRepository
{
    /**
     * Retrieve a category by its ID.
     *
     * @param  string  $id
     * @return Builder|array|Collection|Model
     */
    public function execute(string $id): Builder|array|Collection|Model
    {
        return PostCategory::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
    }
}
