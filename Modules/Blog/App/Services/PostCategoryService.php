<?php

namespace Modules\Blog\App\Services;

use App\Helpers\blog\SlugHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use Modules\Blog\App\Models\PostCategory;
use Modules\Blog\App\Repositories\PostCategoryRepository;
use Modules\Seo\App\Models\post\CategorySchema;
use Ramsey\Uuid\Uuid;

class PostCategoryService
{
    protected PostCategoryRepository $categoryRepository;

    public function __construct(PostCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(
        int $perPage = 15,
        ?string $search = null
    ): LengthAwarePaginator {
        return $this->categoryRepository->getAllCategories($perPage, $search);
    }

    public function getCategoryById(string $id): array|Builder|Collection|Model
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    public function getCategoryBySlug(string $slug): Builder|Model
    {
        return $this->categoryRepository->getCategoryBySlug($slug);
    }

    /**
     * @throws ValidationException
     */
    public function createCategory(array $data, string $userId): PostCategory
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

            $category = $this->categoryRepository->createCategory($categoryData);

            if (!empty($data['seo'])) {
                $seoData = array_merge($data['seo'], [
                    'id' => Uuid::uuid4()->toString(),
                    'category_id' => $category->id,
                    'created_by' => $userId,
                    'generator' => 'Spital CMS, Created By Abrecode.com',
                ]);
                $this->categoryRepository->createCategorySeo($category->id, $seoData);
            }

            if (!empty($data['schema']) && !empty($data['schema']['type'])) {
                $schemaData = [
                    'id' => Uuid::uuid4()->toString(),
                    'category_id' => $category->id,
                    'type' => $data['schema']['type'] ?? null,
                    'title' => $data['schema']['title'] ?? $data['name'],
                    'slug' => $data['schema']['slug'] ?? SlugHelper::generatePersianSlug(
                            $data['schema']['title'] ?? $data['title'],
                            CategorySchema::class,
                            'slug'
                        ), // استفاده از هلپر برای اسکیما
                    'content' => $data['schema']['content'] ?? null,
                    'description' => $data['schema']['description'] ?? null,
                    'data' => !empty($data['schema']['data']) ? $data['schema']['data'] : null,
                    'schema_json' => $this->buildSchemaJson(
                        $data['schema']['type'] ?? '',
                        $data['schema']['data'] ?? []
                    ),
                    'status' => $data['schema']['status'] ?? 'draft',
                    'visibility' => $data['schema']['visibility'] ?? 'public',
                    'language' => $data['schema']['language'] ?? 'fa',
                    'created_by' => $userId,
                ];
                $this->categoryRepository->createCategorySchema($category->id, $schemaData);
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

    public function updateCategory(string $id, array $data, string $userId): PostCategory
    {
        $categoryData = array_filter([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'] ?? null,
            'slug' => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? null,
            'updated_by' => $userId,
        ]);

        $category = $this->categoryRepository->updateCategory($id, $categoryData);

        if (!empty($data['seo'])) {
            $seoData = array_merge($data['seo'], ['created_by' => $userId]);
            $this->categoryRepository->updateCategorySeo($category->id, $seoData);
        }

        if (!empty($data['schema']) && !empty($data['schema']['type'])) {
            $schemaData = [
                'type' => $data['schema']['type'] ?? null,
                'title' => $data['schema']['title'] ?? $data['name'],
                'slug' => $data['schema']['slug'] ?? ($data['slug'] ?? Str::slug($data['name'], '-', 'fa')),
                'content' => $data['schema']['content'] ?? null,
                'description' => $data['schema']['description'] ?? null,
                'data' => !empty($data['schema']['data']) ? $data['schema']['data'] : null,
                'schema_json' => $this->buildSchemaJson(
                    $data['schema']['type'] ?? '',
                    $data['schema']['data'] ?? []
                ),
                'status' => $data['schema']['status'] ?? 'draft',
                'visibility' => $data['schema']['visibility'] ?? 'public',
                'language' => $data['schema']['language'] ?? 'fa',
                'created_by' => $userId,
            ];
            $this->categoryRepository->updateCategorySchema($category->id, $schemaData);
        }

        return $category;
    }

    public function deleteCategory(string $id): void
    {
        $this->categoryRepository->deleteCategory($id);
    }

    protected function buildSchemaJson(string $type, array $data): array
    {
        $schema = ['@context' => 'https://schema.org', '@type' => $type];

        switch ($type) {
            case 'CollectionPage':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'url' => $data['url'] ?? null,
                ]);
                break;
            case 'BreadcrumbList':
                $schema['itemListElement'] = array_map(function ($item, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $item['name'] ?? null,
                        'item' => $item['item'] ?? null,
                    ];
                }, $data['itemListElement'] ?? []);
                break;
        }

        return array_filter($schema, fn($value) => !is_null($value));
    }
}
