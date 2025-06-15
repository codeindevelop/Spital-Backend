<?php

namespace Modules\Blog\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Settings\App\Services\Blog\BlogSettingService;

/**
 * @property mixed $schema
 * @property mixed $seo
 * @property mixed $published_at
 * @property mixed $visibility
 * @property mixed $status
 * @property mixed $author
 * @property mixed $cover_image_height
 * @property mixed $cover_image_width
 * @property mixed $cover_image_alt
 * @property mixed $cover_image_name
 * @property mixed $cover_image_id
 * @property mixed $comments
 * @property mixed $likes_count
 * @property mixed $summary
 * @property mixed $category
 * @property mixed $slug
 * @property mixed $title
 * @property mixed $id
 */
class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        $settings = app(BlogSettingService::class)->getSettings();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : ['name' => 'دسته‌بندی نشده'],
            'summary' => $this->summary,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments->count(),
            'cover_image' => [
                'id' => $this->cover_image_id,
                'name' => $this->cover_image_name,
                'alt' => $this->cover_image_alt,
                'preview' => $this->cover_image_preview ?? $settings->default_cover_image,
                'width' => $this->cover_image_width,
                'height' => $this->cover_image_height,
            ],
            'font_family' => $settings->font_family,
            'author' => [
                'userId' => $this->author->id,
                'fullName' => $this->author->name,
                'avatarId' => $this->author->avatar_id,
                'articlesCount' => $this->author->articles_count,
            ],
            'status' => $this->status,
            'visibility' => $this->visibility,
            'published_at' => $this->published_at,
            'seo' => $this->seo,
            'schema' => $this->schema,
        ];
    }
}
