<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Support\Facades\Log;
use Modules\Blog\App\Repositories\Posts\DeleteBookmarkRepository;
use Modules\Blog\App\Repositories\Posts\FindBookmarkRepository;

class UnbookmarkPostService
{
    protected FindBookmarkRepository $bookmarkRepository;
    protected DeleteBookmarkRepository $deleteBookmarkRepository;

    public function __construct(
        FindBookmarkRepository $bookmarkRepository,
        DeleteBookmarkRepository $deleteBookmarkRepository
    ) {
        $this->bookmarkRepository = $bookmarkRepository;
        $this->deleteBookmarkRepository = $deleteBookmarkRepository;
    }

    /**
     * Unbookmark a post.
     *
     * @param  string  $postId
     * @param  string  $userId
     * @return void
     * @throws \Exception
     */
    public function execute(string $postId, string $userId): void
    {
        try {
            $bookmark = $this->bookmarkRepository->execute($postId, $userId);
            if (!$bookmark) {
                throw new \Exception('شما این پست را بوکمارک نکرده‌اید.');
            }

            $this->deleteBookmarkRepository->execute($bookmark->id);
        } catch (\Exception $e) {
            Log::error('Error unbookmarking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
