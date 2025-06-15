<?php

namespace Modules\Blog\App\Services\Categories;

class BuildSchemaJsonService
{
    /**
     * Build JSON schema for a category.
     *
     * @param  string  $type
     * @param  array  $data
     * @return array
     */
    public function execute(string $type, array $data): array
    {
        $schema = ['@context' => 'https://schema.org', '@type' => $type];

        switch ($type) {
            case 'CollectionPage':
                $schema = array_merge($schema, [
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'url' => $data['url'] ?? null,
                ]);
                break;
            case 'BreadcrumbList':
                $schema['itemListElement'] = array_map(function ($item, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $item['name'] ?? null,
                        'item' => $item['item'] ?? null,
                    ];
                }, $data['itemListElement'] ?? []);
                break;
        }

        return array_filter($schema, fn($value) => !is_null($value));
    }
}
