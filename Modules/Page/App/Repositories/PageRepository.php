<?php

namespace Modules\Page\App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Page\App\Models\Page;
use Modules\Seo\App\Models\page\PageSchema;
use Modules\Seo\App\Models\page\PageSeo;

class PageRepository
{
    public function getAllPages(
        int $perPage,
        ?string $status = null,
        ?string $visibility = null,
        ?string $search = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        $query = Page::query()
            ->with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->when($status, fn(Builder $q) => $q->where('status', $status))
            ->when($visibility, fn(Builder $q) => $q->where('visibility', $visibility))
            ->when($search, fn(Builder $q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%"));

        return $query->paginate($perPage);
    }

    public function getPageById(string $id
    ): Builder|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model {
        return Page::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
    }

    public function getPageBySlug(string $slug): Builder|\Illuminate\Database\Eloquent\Model
    {
        return Page::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function createPage(array $data): Page
    {
        return Page::create($data);
    }

    public function createPageSeo(string $pageId, array $data): PageSeo
    {
        return PageSeo::create(array_merge($data, ['page_id' => $pageId]));
    }

    public function createPageSchema(string $pageId, array $data): PageSchema
    {
        return PageSchema::create(array_merge($data, ['page_id' => $pageId]));
    }

    public function updatePage(string $id, array $data): Page
    {
        $page = Page::findOrFail($id);
        $page->update(array_filter($data));
        return $page;
    }

    public function updatePageSeo(string $pageId, array $data): PageSeo
    {
        $seo = PageSeo::where('page_id', $pageId)->first();
        if ($seo) {
            $seo->update(array_filter($data));
        } else {
            $seo = PageSeo::create(array_merge($data, ['page_id' => $pageId]));
        }
        return $seo;
    }

    public function updatePageSchema(string $pageId, array $data): PageSchema
    {
        $schema = PageSchema::where('page_id', $pageId)->first();
        if ($schema) {
            $schema->update(array_filter($data));
        } else {
            $schema = PageSchema::create(array_merge($data, ['page_id' => $pageId]));
        }
        return $schema;
    }

    public function deletePage(string $id): void
    {
        $page = Page::findOrFail($id);
        $page->delete();
    }
}
