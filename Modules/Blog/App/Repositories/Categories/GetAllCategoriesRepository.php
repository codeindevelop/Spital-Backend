<?php

namespace Modules\Blog\App\Repositories\Categories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\App\Models\PostCategory;

class GetAllCategoriesRepository
{
    /**
     * Retrieve all categories with pagination and optional search.
     *
     * @param  int  $perPage
     * @param  string|null  $search
     * @return LengthAwarePaginator
     */
    public function execute(int $perPage, ?string $search = null): LengthAwarePaginator
    {
        $query = PostCategory::query()
            ->with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->when($search, fn(Builder $q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%"));

        return $query->paginate($perPage);
    }
}
