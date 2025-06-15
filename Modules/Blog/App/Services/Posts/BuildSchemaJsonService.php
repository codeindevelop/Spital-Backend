<?php

namespace Modules\Blog\App\Services\Posts;

class BuildSchemaJsonService
{
    /**
     * Build JSON schema for a post.
     *
     * @param  string  $type
     * @param  array  $data
     * @return array
     */
    public function execute(string $type, array $data): array
    {
        $schema = ['@context' => 'https://schema.org', '@type' => $type];

        switch ($type) {
            case 'Article':
                $schema = array_merge($schema, [
                    'headline' => $data['headline'] ?? null,
                    'description' => $data['description'] ?? null,
                    'author' => $data['author'] ?? null,
                    'datePublished' => $data['datePublished'] ?? null,
                    'image' => $data['image'] ?? null,
                ]);
                break;
            case 'FAQPage':
                $schema['mainEntity'] = array_map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['question'] ?? null,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'] ?? null,
                        ],
                    ];
                }, $data['faq'] ?? []);
                break;
        }

        return array_filter($schema, fn($value) => !is_null($value));
    }
}
