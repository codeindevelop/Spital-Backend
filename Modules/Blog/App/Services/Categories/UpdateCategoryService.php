<?php

namespace Modules\Blog\App\Services\Categories;

use Illuminate\Support\Str;
use Modules\Blog\App\Models\PostCategory;
use Modules\Blog\App\Repositories\Categories\UpdateCategoryRepository;
use Modules\Blog\App\Repositories\Categories\UpdateCategorySeoRepository;
use Modules\Blog\App\Repositories\Categories\UpdateCategorySchemaRepository;

class UpdateCategoryService
{
    protected UpdateCategoryRepository $categoryRepository;
    protected UpdateCategorySeoRepository $seoRepository;
    protected UpdateCategorySchemaRepository $schemaRepository;
    protected BuildSchemaJsonService $schemaJsonService;

    public function __construct(
        UpdateCategoryRepository $categoryRepository,
        UpdateCategorySeoRepository $seoRepository,
        UpdateCategorySchemaRepository $schemaRepository,
        BuildSchemaJsonService $schemaJsonService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->seoRepository = $seoRepository;
        $this->schemaRepository = $schemaRepository;
        $this->schemaJsonService = $schemaJsonService;
    }

    /**
     * Update a category with SEO and schema data.
     *
     * @param  string  $id
     * @param  array  $data
     * @param  string  $userId
     * @return PostCategory
     */
    public function execute(string $id, array $data, string $userId): PostCategory
    {
        $categoryData = array_filter([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'] ?? null,
            'slug' => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? null,
            'updated_by' => $userId,
        ]);

        $category = $this->categoryRepository->execute($id, $categoryData);

        if (!empty($data['seo'])) {
            $seoData = array_merge($data['seo'], ['created_by' => $userId]);
            $this->seoRepository->execute($category->id, $seoData);
        }

        if (!empty($data['schema']) && !empty($data['schema']['type'])) {
            $schemaData = [
                'type' => $data['schema']['type'] ?? null,
                'title' => $data['schema']['title'] ?? $data['name'],
                'slug' => $data['schema']['slug'] ?? ($data['slug'] ?? Str::slug($data['name'], '-', 'fa')),
                'content' => $data['schema']['content'] ?? null,
                'description' => $data['schema']['description'] ?? null,
                'data' => !empty($data['schema']['data']) ? $data['schema']['data'] : null,
                'schema_json' => $this->schemaJsonService->execute(
                    $data['schema']['type'] ?? '',
                    $data['schema']['data'] ?? []
                ),
                'status' => $data['schema']['status'] ?? 'draft',
                'visibility' => $data['schema']['visibility'] ?? 'public',
                'language' => $data['schema']['language'] ?? 'fa',
                'created_by' => $userId,
            ];
            $this->schemaRepository->execute($category->id, $schemaData);
        }

        return $category;
    }
}
