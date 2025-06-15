<?php

namespace Modules\Blog\App\Services\Categories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Repositories\Categories\GetCategoryBySlugRepository;

class GetCategoryBySlugService
{
    protected GetCategoryBySlugRepository $categoryRepository;

    public function __construct(GetCategoryBySlugRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Retrieve a category by its slug.
     *
     * @param  string  $slug
     * @return Builder|Model
     */
    public function execute(string $slug): Builder|Model
    {
        return $this->categoryRepository->execute($slug);
    }
}
