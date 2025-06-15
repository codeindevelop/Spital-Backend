<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Support\Facades\Log;
use Modules\Blog\App\Repositories\Posts\DeleteLikeRepository;
use Modules\Blog\App\Repositories\Posts\FindLikeRepository;
use Modules\Blog\App\Repositories\Posts\GetPostByIdRepository;
use Modules\Blog\App\Repositories\Posts\DecrementLikesCountRepository;

class UnlikePostService
{
    protected GetPostByIdRepository $postRepository;
    protected FindLikeRepository $likeRepository;
    protected DeleteLikeRepository $deleteLikeRepository;
    protected DecrementLikesCountRepository $decrementLikesRepository;

    public function __construct(
        GetPostByIdRepository $postRepository,
        FindLikeRepository $likeRepository,
        DeleteLikeRepository $deleteLikeRepository,
        DecrementLikesCountRepository $decrementLikesRepository
    ) {
        $this->postRepository = $postRepository;
        $this->likeRepository = $likeRepository;
        $this->deleteLikeRepository = $deleteLikeRepository;
        $this->decrementLikesRepository = $decrementLikesRepository;
    }

    /**
     * Unlike a post.
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

            $like = $this->likeRepository->execute($postId, $userId);
            if (!$like) {
                throw new \Exception('شما این پست را لایک نکرده‌اید.');
            }

            $this->deleteLikeRepository->execute($like->id);
            $this->decrementLikesRepository->execute($postId);
        } catch (\Exception $e) {
            Log::error('Error unliking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
