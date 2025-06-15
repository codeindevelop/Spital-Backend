<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Support\Facades\Log;
use Modules\Blog\App\Repositories\Posts\CreateViewRepository;
use Modules\Blog\App\Repositories\Posts\GetViewsCountRepository;
use Modules\Blog\App\Repositories\Posts\AddToTrendingRepository;
use Ramsey\Uuid\Uuid;

class RecordViewService
{
    protected CreateViewRepository $viewRepository;
    protected GetViewsCountRepository $viewsCountRepository;
    protected AddToTrendingRepository $trendingRepository;

    public function __construct(
        CreateViewRepository $viewRepository,
        GetViewsCountRepository $viewsCountRepository,
        AddToTrendingRepository $trendingRepository
    ) {
        $this->viewRepository = $viewRepository;
        $this->viewsCountRepository = $viewsCountRepository;
        $this->trendingRepository = $trendingRepository;
    }

    /**
     * Record a view for a post.
     *
     * @param  string  $postId
     * @param  string|null  $userId
     * @param  string  $ipAddress
     * @return void
     */
    public function execute(string $postId, ?string $userId, string $ipAddress): void
    {
        try {
            $viewData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $postId,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'viewed_at' => now(),
            ];

            $this->viewRepository->execute($viewData);

            // بررسی برای افزودن به پربازدیدها
            $viewsCount = $this->viewsCountRepository->execute($postId);
            if ($viewsCount > 5) { // آستانه ۵ بازدید
                $this->trendingRepository->execute($postId, $viewsCount);
            }
        } catch (\Exception $e) {
            Log::error('Error recording view: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'ipAddress' => $ipAddress,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
