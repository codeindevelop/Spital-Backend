<?php

namespace Modules\Blog\App\Services\Categories;

use Modules\Blog\App\Repositories\Categories\DeleteCategoryRepository;

class DeleteCategoryService
{
    protected DeleteCategoryRepository $categoryRepository;

    public function __construct(DeleteCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Delete a category by its ID.
     *
     * @param  string  $id
     * @return void
     */
    public function execute(string $id): void
    {
        $this->categoryRepository->execute($id);
    }
}
