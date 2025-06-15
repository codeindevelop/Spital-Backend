<?php

namespace Modules\Blog\App\Services\Categories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Repositories\Categories\GetAllCategoriesRepository;

class GetAllCategoriesService
{
    protected GetAllCategoriesRepository $categoryRepository;

    public function __construct(GetAllCategoriesRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Retrieve all categories with pagination and optional search.
     *
     * @param  int  $perPage
     * @param  string|null  $search
     * @return LengthAwarePaginator
     */
    public function execute(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->categoryRepository->execute($perPage, $search);
    }
}
