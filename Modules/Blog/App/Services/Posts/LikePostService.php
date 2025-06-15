<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Support\Facades\Log;
use Modules\Blog\App\Repositories\Posts\CreateLikeRepository;
use Modules\Blog\App\Repositories\Posts\FindLikeRepository;
use Modules\Blog\App\Repositories\Posts\GetPostByIdRepository;
use Modules\Blog\App\Repositories\Posts\IncrementLikesCountRepository;
use Ramsey\Uuid\Uuid;

class LikePostService
{
    protected GetPostByIdRepository $postRepository;
    protected FindLikeRepository $likeRepository;
    protected CreateLikeRepository $createLikeRepository;
    protected IncrementLikesCountRepository $incrementLikesRepository;

    public function __construct(
        GetPostByIdRepository $postRepository,
        FindLikeRepository $likeRepository,
        CreateLikeRepository $createLikeRepository,
        IncrementLikesCountRepository $incrementLikesRepository
    ) {
        $this->postRepository = $postRepository;
        $this->likeRepository = $likeRepository;
        $this->createLikeRepository = $createLikeRepository;
        $this->incrementLikesRepository = $incrementLikesRepository;
    }

    /**
     * Like a post.
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

            $existingLike = $this->likeRepository->execute($postId, $userId);
            if ($existingLike) {
                throw new \Exception('شما قبلاً این پست را لایک کرده‌اید.');
            }

            $likeData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $postId,
                'user_id' => $userId,
                'liked_at' => now(),
            ];

            $this->createLikeRepository->execute($likeData);
            $this->incrementLikesRepository->execute($postId);
        } catch (\Exception $e) {
            Log::error('Error liking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
