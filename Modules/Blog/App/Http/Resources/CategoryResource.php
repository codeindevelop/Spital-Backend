<?php

namespace Modules\Blog\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $slug
 * @property mixed $description
 * @property mixed $parent
 * @property mixed $is_active
 * @property mixed $seo
 * @property mixed $schema
 */
class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent' => $this->parent ? [
                'id' => $this->parent->id,
                'name' => $this->parent->name,
                'slug' => $this->parent->slug,
            ] : null,
            'is_active' => $this->is_active,
            'seo' => $this->seo,
            'schema' => $this->schema,
        ];
    }
}
