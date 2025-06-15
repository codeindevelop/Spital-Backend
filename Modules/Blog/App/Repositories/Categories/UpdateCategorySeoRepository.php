<?php

namespace Modules\Blog\App\Repositories\Categories;

use Modules\Seo\App\Models\post\CategorySeo;

class UpdateCategorySeoRepository
{
    /**
     * Update or create SEO data for a category.
     *
     * @param  string  $categoryId
     * @param  array  $data
     * @return CategorySeo
     */
    public function execute(string $categoryId, array $data): CategorySeo
    {
        $seo = CategorySeo::where('category_id', $categoryId)->first();
        if ($seo) {
            $seo->update(array_filter($data));
        } else {
            $seo = CategorySeo::create(array_merge($data, ['category_id' => $categoryId]));
        }
        return $seo;
    }
}
