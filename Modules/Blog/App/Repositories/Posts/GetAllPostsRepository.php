<?php

namespace Modules\Blog\App\Repositories\Posts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Models\Post;

class GetAllPostsRepository
{
    public function execute(
        int $perPage,
        ?string $status = null,
        ?string $visibility = null,
        ?string $search = null,
        ?string $categoryId = null
    ): LengthAwarePaginator {
        $query = Post::query()->with(['category', 'comments']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $categoryIds = $this->getChildCategoryIds($categoryId);
            $query->whereIn('category_id', $categoryIds);
        }

        return $query->paginate($perPage);
    }

    protected function getChildCategoryIds(string $categoryId): array
    {
        // این متد باید از PostRepository اصلی منتقل بشه
        return []; // موقتاً خالی
    }
}
