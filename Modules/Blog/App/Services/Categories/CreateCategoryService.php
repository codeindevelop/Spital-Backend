<?php

namespace Modules\Blog\App\Services\Categories;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Blog\App\Models\PostCategory;
use Modules\Blog\App\Repositories\Categories\CreateCategoryRepository;
use Modules\Blog\App\Repositories\Categories\CreateCategorySeoRepository;
use Modules\Blog\App\Repositories\Categories\CreateCategorySchemaRepository;
use Modules\Seo\App\Models\post\CategorySchema;
use Ramsey\Uuid\Uuid;

class CreateCategoryService
{
    protected CreateCategoryRepository $categoryRepository;
    protected CreateCategorySeoRepository $seoRepository;
    protected CreateCategorySchemaRepository $schemaRepository;
    protected BuildSchemaJsonService $schemaJsonService;

    public function __construct(
        CreateCategoryRepository $categoryRepository,
        CreateCategorySeoRepository $seoRepository,
        CreateCategorySchemaRepository $schemaRepository,
        BuildSchemaJsonService $schemaJsonService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->seoRepository = $seoRepository;
        $this->schemaRepository = $schemaRepository;
        $this->schemaJsonService = $schemaJsonService;
    }

    /**
     * Create a new category with SEO and schema data.
     *
     * @param  array  $data
     * @param  string  $userId
     * @return PostCategory
     * @throws ValidationException
     * @throws \Exception
     */
    public function execute(array $data, string $userId): PostCategory
    {
        try {
            if (!empty($data['parent_id'])) {
                $parentExists = PostCategory::where('id', $data['parent_id'])->exists();
                if (!$parentExists) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'دسته‌بندی والد معتبر نیست.',
                    ]);
                }
            }

            $categoryData = [
                'id' => Uuid::uuid4()->toString(),
                'parent_id' => $data['parent_id'] ?? null,
                'name' => $data['name'],
                'slug' => $data['slug'] ?? SlugHelper::generatePersianSlug($data['name'], PostCategory::class, 'slug'),
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ];

            $category = $this->categoryRepository->execute($categoryData);

            if (!empty($data['seo'])) {
                $seoData = array_merge($data['seo'], [
                    'id' => Uuid::uuid4()->toString(),
                    'category_id' => $category->id,
                    'created_by' => $userId,
                    'generator' => 'Spital CMS, Created By Abrecode.com',
                ]);
                $this->seoRepository->execute($category->id, $seoData);
            }

            if (!empty($data['schema']) && !empty($data['schema']['type'])) {
                $schemaData = [
                    'id' => Uuid::uuid4()->toString(),
                    'category_id' => $category->id,
                    'type' => $data['schema']['type'] ?? null,
                    'title' => $data['schema']['title'] ?? $data['name'],
                    'slug' => $data['schema']['slug'] ?? SlugHelper::generatePersianSlug(
                            $data['schema']['title'] ?? $data['name'],
                            CategorySchema::class,
                            'slug'
                        ),
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
        } catch (\Exception $e) {
            Log::error('Error creating category: '.$e->getMessage(), [
                'data' => $data,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
