<?php

namespace Modules\Page\App\Services;

use Illuminate\Support\Str;
use Modules\Page\App\Models\Page;
use Modules\Page\App\Repositories\PageRepository;

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
    ) {
        return $this->pageRepository->getAllPages($perPage, $status, $visibility, $search);
    }

    public function getPageById(string $id): Page
    {
        return $this->pageRepository->getPageById($id);
    }

    public function getPageBySlug(string $slug): Page
    {
        return $this->pageRepository->getPageBySlug($slug);
    }


    public function getPageByPath(array $slugs): Page
    {
        if (empty($slugs)) {
            throw new \Exception('مسیر صفحه نامعتبر است.');
        }

        $childSlug = array_pop($slugs); // slug آخر (فرزند)
        $page = $this->pageRepository->getPageBySlug($childSlug);

        // اگر slug والد وجود داره، چک کن
        if (!empty($slugs)) {
            $parentSlug = end($slugs); // slug والد
            $parent = $this->pageRepository->getPageBySlug($parentSlug);

            if ($page->parent_id !== $parent->id) {
                throw new \Exception('مسیر والد نامعتبر است.');
            }
        } else {
            // اگر slug والد نیست، مطمئن شو صفحه والد نداره
            if ($page->parent_id) {
                throw new \Exception('این صفحه نیاز به مسیر والد دارد.');
            }
        }

        return $page;
    }


    public function createPage(array $data, string $userId): Page
    {
        $pageData = [
            'title' => $data['title'],
            'slug' => $data['slug'] ?? Str::slug($data['title']),
            'description' => $data['description'] ?? null,
            'content' => $data['content'] ?? null,
            'order' => $data['order'] ?? 0,
            'template' => $data['template'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'visibility' => $data['visibility'] ?? 'public',
            'custom_css' => $data['custom_css'] ?? null,
            'custom_js' => $data['custom_js'] ?? null,
            'published_at' => $data['published_at'] ?? now(),
            'is_active' => $data['is_active'] ?? true,
            'parent_id' => $data['parent_id'] ?? null,
            'created_by' => $userId,
        ];

        $page = $this->pageRepository->createPage($pageData);

        if (!empty($data['seo'])) {
            $this->pageRepository->createPageSeo($page->id, array_merge($data['seo'], ['created_by' => $userId]));
        }

        if (!empty($data['schema'])) {
            $this->pageRepository->createPageSchema($page->id, array_merge($data['schema'], ['created_by' => $userId]));
        }

        return $page;
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
            'custom_css' => $data['custom_css'] ?? null,
            'custom_js' => $data['custom_js'] ?? null,
            'published_at' => $data['published_at'] ?? null,
            'is_active' => $data['is_active'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'updated_by' => $userId,
        ]);

        $page = $this->pageRepository->updatePage($id, $pageData);

        if (!empty($data['seo'])) {
            $this->pageRepository->updatePageSeo($page->id, array_merge($data['seo'], ['created_by' => $userId]));
        }

        if (!empty($data['schema'])) {
            $this->pageRepository->updatePageSchema($page->id, array_merge($data['schema'], ['created_by' => $userId]));
        }

        return $page;
    }

    public function deletePage(string $id): void
    {
        $this->pageRepository->deletePage($id);
    }
}
