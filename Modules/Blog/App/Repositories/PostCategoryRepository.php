<?php

namespace Modules\Blog\App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Models\PostCategory;
use Illuminate\Database\Eloquent\Builder;
use Modules\Seo\App\Models\post\CategorySchema;
use Modules\Seo\App\Models\post\CategorySeo;

class PostCategoryRepository
{
    public function getAllCategories(
        int $perPage,
        ?string $search = null
    ): LengthAwarePaginator {
        $query = PostCategory::query()
            ->with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->when($search, fn(Builder $q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%"));

        return $query->paginate($perPage);
    }

    public function getCategoryById(string $id): Builder|array|Collection|Model
    {
        return PostCategory::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
    }

    public function getCategoryBySlug(string $slug): Builder|Model
    {
        return PostCategory::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function createCategory(array $data): PostCategory
    {
        return PostCategory::create($data);
    }

    public function createCategorySeo(string $categoryId, array $data): CategorySeo
    {
        return CategorySeo::create(array_merge($data, ['category_id' => $categoryId]));
    }

    public function createCategorySchema(string $categoryId, array $data): CategorySchema
    {
        return CategorySchema::create(array_merge($data, ['category_id' => $categoryId]));
    }

    public function updateCategory(string $id, array $data): PostCategory
    {
        $category = PostCategory::findOrFail($id);
        $category->update(array_filter($data));
        return $category;
    }

    public function updateCategorySeo(string $categoryId, array $data): CategorySeo
    {
        $seo = CategorySeo::where('category_id', $categoryId)->first();
        if ($seo) {
            $seo->update(array_filter($data));
        } else {
            $seo = CategorySeo::create(array_merge($data, ['category_id' => $categoryId]));
        }
        return $seo;
    }

    public function updateCategorySchema(string $categoryId, array $data): CategorySchema
    {
        $schema = CategorySchema::where('category_id', $categoryId)->first();
        if ($schema) {
            $schema->update(array_filter($data));
        } else {
            $schema = CategorySchema::create(array_merge($data, ['category_id' => $categoryId]));
        }
        return $schema;
    }

    public function deleteCategory(string $id): void
    {
        $category = PostCategory::findOrFail($id);
        $category->delete();
    }
}
