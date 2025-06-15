<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Support\Facades\Log;
use Modules\Blog\App\Repositories\Posts\CreateBookmarkRepository;
use Modules\Blog\App\Repositories\Posts\FindBookmarkRepository;
use Modules\Blog\App\Repositories\Posts\GetPostByIdRepository;
use Ramsey\Uuid\Uuid;

class BookmarkPostService
{
    protected GetPostByIdRepository $postRepository;
    protected FindBookmarkRepository $bookmarkRepository;
    protected CreateBookmarkRepository $createBookmarkRepository;

    public function __construct(
        GetPostByIdRepository $postRepository,
        FindBookmarkRepository $bookmarkRepository,
        CreateBookmarkRepository $createBookmarkRepository
    ) {
        $this->postRepository = $postRepository;
        $this->bookmarkRepository = $bookmarkRepository;
        $this->createBookmarkRepository = $createBookmarkRepository;
    }

    /**
     * Bookmark a post.
     *
     * @param  string  $postId
     * @param  string  $userId
     * @return void
     * @throws \Exception
     */
    public function execute(string $postId, string $userId): void
    {
        try {
            $post = $this->postRepository->execute($postId);
            if (!$post) {
                throw new \Exception('پست یافت نشد.');
            }

            $existingBookmark = $this->bookmarkRepository->execute($postId, $userId);
            if ($existingBookmark) {
                throw new \Exception('شما قبلاً این پست را بوکمارک کرده‌اید.');
            }

            $bookmarkData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $postId,
                'user_id' => $userId,
                'bookmarked_at' => now(),
            ];

            $this->createBookmarkRepository->execute($bookmarkData);
        } catch (\Exception $e) {
            Log::error('Error bookmarking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
