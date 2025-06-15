<?php

namespace Modules\Blog\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $post_id
 * @property mixed $author_name
 * @property mixed $id
 * @property mixed $author_email
 * @property mixed $author_url
 * @property mixed $content
 * @property mixed $status
 * @property mixed $parent_id
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'author_url' => $this->author_url,
            'content' => $this->content,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'children' => CommentResource::collection($this->whenLoaded('children')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
