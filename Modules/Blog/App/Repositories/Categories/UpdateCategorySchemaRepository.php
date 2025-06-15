<?php

namespace Modules\Blog\App\Repositories\Categories;

use Modules\Seo\App\Models\post\CategorySchema;

class UpdateCategorySchemaRepository
{
    /**
     * Update or create schema data for a category.
     *
     * @param  string  $categoryId
     * @param  array  $data
     * @return CategorySchema
     */
    public function execute(string $categoryId, array $data): CategorySchema
    {
        $schema = CategorySchema::where('category_id', $categoryId)->first();
        if ($schema) {
            $schema->update(array_filter($data));
        } else {
            $schema = CategorySchema::create(array_merge($data, ['category_id' => $categoryId]));
        }
        return $schema;
    }
}
