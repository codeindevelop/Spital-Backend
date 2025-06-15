<?php

namespace Modules\Blog\App\Repositories\Categories;

use Modules\Seo\App\Models\post\CategorySeo;

class CreateCategorySeoRepository
{
    /**
     * Create SEO data for a category.
     *
     * @param  string  $categoryId
     * @param  array  $data
     * @return CategorySeo
     */
    public function execute(string $categoryId, array $data): CategorySeo
    {
        return CategorySeo::create(array_merge($data, ['category_id' => $categoryId]));
    }
}
