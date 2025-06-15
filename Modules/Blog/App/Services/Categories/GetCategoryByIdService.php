<?php

namespace Modules\Blog\App\Services\Categories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Repositories\Categories\GetCategoryByIdRepository;

class GetCategoryByIdService
{
    protected GetCategoryByIdRepository $categoryRepository;

    public function __construct(GetCategoryByIdRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Retrieve a category by its ID.
     *
     * @param  string  $id
     * @return Builder|array|Collection|Model
     */
    public function execute(string $id): Builder|array|Collection|Model
    {
        return $this->categoryRepository->execute($id);
    }
}
