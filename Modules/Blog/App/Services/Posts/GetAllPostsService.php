<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Repositories\Posts\GetAllPostsRepository;
use Modules\Settings\App\Services\Blog\BlogSettingService;

class GetAllPostsService
{
    protected GetAllPostsRepository $postRepository;
    protected BlogSettingService $blogSettingService;

    public function __construct(GetAllPostsRepository $postRepository, BlogSettingService $blogSettingService)
    {
        $this->postRepository = $postRepository;
        $this->blogSettingService = $blogSettingService;
    }

    public function execute(
        ?int $perPage = null,
        ?string $status = null,
        ?string $visibility = null,
        ?string $search = null,
        ?string $categoryId = null
    ): LengthAwarePaginator {
        $settings = $this->blogSettingService->getSettings();
        $perPage = $perPage ?? ($visibility === 'public' ? $settings->public_posts_per_page : $settings->admin_posts_per_page);

        return $this->postRepository->execute($perPage, $status, $visibility, $search, $categoryId);
    }
}
