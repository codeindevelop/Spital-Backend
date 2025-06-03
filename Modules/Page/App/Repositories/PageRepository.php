<?php

namespace Modules\Page\App\Repositories;

use Illuminate\Support\Str;
use Modules\Page\App\Models\Page;

use Illuminate\Database\Eloquent\Builder;
use Modules\Seo\App\Models\PageSchema;
use Modules\Seo\App\Models\PageSeo;

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
            ->when($search,
                fn(Builder $q) => $q->where('title', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%"));

        return $query->paginate($perPage);
    }

    public function getPageById(string $id
    ): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Builder|array|null {
        return Page::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
    }

    public function getPageBySlug(string $slug): \Illuminate\Database\Eloquent\Model|Builder
    {
        return Page::with(['parent', 'children', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function createPage(array $data): Page
    {
        $page = Page::create([
            'id' => (string) Str::uuid(),
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
            'created_by' => $data['created_by'],
        ]);

        return $page;
    }

    public function createPageSeo(string $pageId, array $data)
    {
        return PageSeo::create([
            'id' => (string) Str::uuid(),
            'page_id' => $pageId,
            'created_by' => $data['created_by'],
            'meta_title' => $data['meta_title'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
            'favicon' => $data['favicon'] ?? null,
            'robots_index' => $data['robots_index'] ?? 'index',
            'robots_follow' => $data['robots_follow'] ?? 'follow',
            'theme_color' => $data['theme_color'] ?? null,
            'language' => $data['language'] ?? 'en',
            'region' => $data['region'] ?? null,
            'timezone' => $data['timezone'] ?? 'UTC',
            'author' => $data['author'] ?? null,
            'og_title' => $data['og_title'] ?? null,
            'og_description' => $data['og_description'] ?? null,
            'og_image' => $data['og_image'] ?? null,
            'og_type' => $data['og_type'] ?? 'website',
            'og_url' => $data['og_url'] ?? null,
            'og_site_name' => $data['og_site_name'] ?? null,
            'og_locale' => $data['og_locale'] ?? 'en_US',
            'og_image_alt' => $data['og_image_alt'] ?? null,
            'og_image_width' => $data['og_image_width'] ?? null,
            'og_image_height' => $data['og_image_height'] ?? null,
            'og_image_type' => $data['og_image_type'] ?? 'image/jpeg',
            'twitter_title' => $data['twitter_title'] ?? null,
            'twitter_description' => $data['twitter_description'] ?? null,
            'twitter_site' => $data['twitter_site'] ?? null,
            'twitter_creator' => $data['twitter_creator'] ?? null,
            'twitter_image' => $data['twitter_image'] ?? null,
            'twitter_card_type' => $data['twitter_card_type'] ?? 'summary_large_image',
            'twitter_site_handle' => $data['twitter_site_handle'] ?? null,
            'twitter_creator_handle' => $data['twitter_creator_handle'] ?? null,
            'twitter_image_alt' => $data['twitter_image_alt'] ?? null,
            'twitter_image_width' => $data['twitter_image_width'] ?? null,
            'twitter_image_height' => $data['twitter_image_height'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function createPageSchema(string $pageId, array $data)
    {
        return PageSchema::create([
            'id' => (string) Str::uuid(),
            'page_id' => $pageId,
            'created_by' => $data['created_by'],
            'title' => $data['title'] ?? null,
            'slug' => $data['slug'] ?? Str::slug($data['title'] ?? $data['title']),
            'content' => $data['content'] ?? null,
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
            'tags' => $data['tags'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'visibility' => $data['visibility'] ?? 'public',
            'access_level' => $data['access_level'] ?? 'everyone',
            'type' => $data['type'] ?? 'schema',
            'version' => $data['version'] ?? '1.0',
            'order' => $data['order'] ?? 0,
            'keywords' => $data['keywords'] ?? null,
            'language' => $data['language'] ?? 'en',
            'region' => $data['region'] ?? null,
            'timezone' => $data['timezone'] ?? 'UTC',
            'icon' => $data['icon'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'thumbnail_image' => $data['thumbnail_image'] ?? null,
            'banner_image' => $data['banner_image'] ?? null,
            'video_url' => $data['video_url'] ?? null,
            'audio_url' => $data['audio_url'] ?? null,
            'external_link' => $data['external_link'] ?? null,
            'template' => $data['template'] ?? null,
            'layout' => $data['layout'] ?? 'default',
            'theme' => $data['theme'] ?? 'default',
            'author' => $data['author'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'date_published' => $data['date_published'] ?? null,
            'date_modified' => $data['date_modified'] ?? null,
            'schema_data' => $data['schema_data'] ?? null,
            'schema_json' => $data['schema_json'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updatePage(string $id, array $data)
    {
        $page = Page::findOrFail($id);
        $page->update(array_filter($data));
        return $page;
    }

    public function updatePageSeo(string $pageId, array $data): PageSeo
    {
        $seo = PageSeo::where('page_id', $pageId)->firstOrFail();
        $seo->update(array_filter($data));
        return $seo;
    }

    public function updatePageSchema(string $pageId, array $data): PageSchema
    {
        $schema = PageSchema::where('page_id', $pageId)->firstOrFail();
        $schema->update(array_filter($data));
        return $schema;
    }

    public function deletePage(string $id): void
    {
        $page = Page::findOrFail($id);
        $page->delete();
    }
}
