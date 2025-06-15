<?php

namespace Modules\Blog\App\Repositories\Categories;

use Modules\Seo\App\Models\post\CategorySchema;

class CreateCategorySchemaRepository
{
    /**
     * Create schema data for a category.
     *
     * @param  string  $categoryId
     * @param  array  $data
     * @return CategorySchema
     */
    public function execute(string $categoryId, array $data): CategorySchema
    {
        return CategorySchema::create(array_merge($data, ['category_id' => $categoryId]));
    }
}
