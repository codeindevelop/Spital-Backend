<?php

namespace Modules\Page\App\Services;

use Modules\Page\App\Models\Page;
use Modules\Page\App\Repositories\PageRepository;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PageService
{
    protected PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getAllPages(
        int $perPage = 15,
        ?string $status = null,
        ?string $visibility = null,
        ?string $search = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        return $this->pageRepository->getAllPages($perPage, $status, $visibility, $search);
    }

    public function getPageById(string $id
    ): array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model {
        return $this->pageRepository->getPageById($id);
    }

    public function getPageBySlug(string $slug
    ): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model {
        return $this->pageRepository->getPageBySlug($slug);
    }

    public function getPageByPath(array $slugs
    ): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model {
        if (empty($slugs)) {
            throw new \Exception('مسیر صفحه نامعتبر است.');
        }

        $childSlug = array_pop($slugs);
        $page = $this->pageRepository->getPageBySlug($childSlug);

        if (!empty($slugs)) {
            $parentSlug = end($slugs);
            $parent = $this->pageRepository->getPageBySlug($parentSlug);

            if ($page->parent_id !== $parent->id) {
                throw new \Exception('مسیر والد نامعتبر است.');
            }
        } else {
            if ($page->parent_id) {
                throw new \Exception('این صفحه نیاز به مسیر والد دارد.');
            }
        }

        return $page;
    }

    public function createPage(array $data, string $userId): Page
    {
        try {
            if (!empty($data['parent_id'])) {
                $parentExists = Page::where('id', $data['parent_id'])->exists();
                if (!$parentExists) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'صفحه والد معتبر نیست.',
                    ]);
                }
            }

            $pageData = [
                'id' => Uuid::uuid4()->toString(),
                'title' => $data['title'],
                'slug' => $data['slug'] ?? Str::slug($data['title']),
                'description' => $data['description'] ?? null,
                'content' => $data['content'] ?? null,
                'order' => $data['order'] ?? 0,
                'template' => $data['template'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'visibility' => $data['visibility'] ?? 'public',
                'password' => !empty($data['password']) ? bcrypt($data['password']) : null,
                'custom_css' => $data['custom_css'] ?? null,
                'custom_js' => $data['custom_js'] ?? null,
                'published_at' => !empty($data['published_at']) && is_string($data['published_at']) && strtotime($data['published_at']) ? $data['published_at'] : null,
                'is_active' => $data['is_active'] ?? true,
                'parent_id' => !empty($data['parent_id']) && is_string($data['parent_id']) ? $data['parent_id'] : null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ];

            $page = $this->pageRepository->createPage($pageData);

            if (!empty($data['seo'])) {
                $seoData = array_merge($data['seo'], [
                    'id' => Uuid::uuid4()->toString(),
                    'page_id' => $page->id,
                    'created_by' => $userId,
                ]);
                $this->pageRepository->createPageSeo($page->id, $seoData);
            }

            if (!empty($data['schema']) && !empty($data['schema']['type'])) {
                $schemaData = [
                    'id' => Uuid::uuid4()->toString(),
                    'page_id' => $page->id,
                    'type' => $data['schema']['type'] ?? null,
                    'title' => $data['schema']['title'] ?? $data['title'],
                    'slug' => $data['schema']['slug'] ?? ($data['slug'] ?? Str::slug($data['title'])),
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
                $this->pageRepository->createPageSchema($page->id, $schemaData);
            }

            return $page;
        } catch (\Exception $e) {
            Log::error('Error creating page: '.$e->getMessage(), [
                'data' => $data,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    protected function buildSchemaJson(string $type, array $data): array
    {
        $schema = ['@context' => 'https://schema.org', '@type' => $type];

        switch ($type) {
            case 'Website':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'url' => $data['url'] ?? null,
                ]);
                break;
            case 'Article':
                $schema = array_merge($schema, [
                    'headline' => $data['headline'] ?? null,
                    'description' => $data['description'] ?? null,
                    'author' => $data['author'] ?? null,
                    'datePublished' => $data['datePublished'] ?? null,
                    'image' => $data['image'] ?? null,
                ]);
                break;
            case 'Product':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'sku' => $data['sku'] ?? null,
                    'price' => $data['price'] ?? null,
                    'priceCurrency' => $data['currency'] ?? 'USD',
                    'image' => $data['image'] ?? null,
                    'brand' => $data['brand'] ?? null,
                ]);
                break;
            case 'FAQPage':
                $schema['mainEntity'] = array_map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['question'] ?? null,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'] ?? null, // اصلاح
                        ],
                    ];
                }, $data['faq'] ?? []);
                break;
            case 'Event':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'startDate' => $data['startDate'] ?? null,
                    'endDate' => $data['endDate'] ?? null,
                    'location' => $data['location'] ?? null,
                    'image' => $data['image'] ?? null,
                ]);
                break;
            case 'LocalBusiness':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'address' => $data['address'] ?? null,
                    'telephone' => $data['telephone'] ?? null,
                    'openingHours' => $data['openingHours'] ?? null,
                    'image' => $data['image'] ?? null,
                ]);
                break;
            case 'Organization':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'url' => $data['url'] ?? null,
                    'logo' => $data['logo'] ?? null,
                    'contactPoint' => $data['contactPoint'] ?? null,
                    'sameAs' => !empty($data['sameAs']) ? array_map('trim', explode(',', $data['sameAs'])) : null,
                ]);
                break;
            case 'Review':
                $schema = array_merge($schema, [
                    'itemReviewed' => ['@type' => 'Thing', 'name' => $data['itemReviewed'] ?? null],
                    'reviewBody' => $data['reviewBody'] ?? null,
                    'author' => ['@type' => 'Person', 'name' => $data['author'] ?? null],
                    'reviewRating' => [
                        '@type' => 'Rating',
                        'ratingValue' => $data['ratingValue'] ?? null,
                        'bestRating' => $data['bestRating'] ?? 5,
                    ],
                ]);
                break;
            case 'Recipe':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'prepTime' => $data['prepTime'] ?? null,
                    'cookTime' => $data['cookTime'] ?? null,
                    'recipeIngredient' => !empty($data['recipeIngredient']) ? array_map('trim',
                        explode(',', $data['recipeIngredient'])) : null,
                    'image' => $data['image'] ?? null,
                ]);
                break;
            case 'VideoObject':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'thumbnailUrl' => $data['thumbnailUrl'] ?? null,
                    'contentUrl' => $data['contentUrl'] ?? null,
                    'uploadDate' => $data['uploadDate'] ?? null,
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
            case 'Custom':
                $schema = array_merge($schema,
                    !empty($data['customJson']) ? json_decode($data['customJson'], true) : []);
                break;
        }

        return array_filter($schema, fn($value) => !is_null($value));
    }

    public function updatePage(string $id, array $data, string $userId): Page
    {
        $pageData = array_filter([
            'title' => $data['title'] ?? null,
            'slug' => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'content' => $data['content'] ?? null,
            'order' => $data['order'] ?? null,
            'template' => $data['template'] ?? null,
            'status' => $data['status'] ?? null,
            'visibility' => $data['visibility'] ?? null,
            'password' => !empty($data['password']) ? bcrypt($data['password']) : null,
            'custom_css' => $data['custom_css'] ?? null,
            'custom_js' => $data['custom_js'] ?? null,
            'published_at' => $data['published_at'] ?? null,
            'is_active' => $data['is_active'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'updated_by' => $userId,
        ]);

        $page = $this->pageRepository->updatePage($id, $pageData);

        if (!empty($data['seo'])) {
            $seoData = array_merge($data['seo'], ['created_by' => $userId]);
            $this->pageRepository->updatePageSeo($page->id, $seoData);
        }

        if (!empty($data['schema']) && !empty($data['schema']['type'])) {
            $schemaData = [
                'type' => $data['schema']['type'] ?? null,
                'title' => $data['schema']['title'] ?? $data['title'],
                'slug' => $data['schema']['slug'] ?? ($data['slug'] ?? Str::slug($data['title'])),
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
            $this->pageRepository->updatePageSchema($page->id, $schemaData);
        }

        return $page;
    }

    public function deletePage(string $id): void
    {
        $this->pageRepository->deletePage($id);
    }
}
