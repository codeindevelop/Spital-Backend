<?php

namespace Modules\Blog\App\Repositories\Categories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Models\PostCategory;

class GetCategoryBySlugRepository
{
    /**
     * Retrieve a category by its slug.
     *
     * @param  string  $slug
     * @return Builder|Model
     */
    public function execute(string $slug): Builder|Model
    {
        return PostCategory::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
