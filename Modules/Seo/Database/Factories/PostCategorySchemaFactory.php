<?php

namespace Modules\Seo\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Seo\App\Models\post\CategorySchema;
use Modules\Settings\App\Models\Blog\PostCategory;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;
use Ybazli\Faker\Faker;

class PostCategorySchemaFactory extends Factory
{
    protected $model = CategorySchema::class;

    public function definition(): array
    {
        $faker = new Faker();
        $title = $faker->sentence(4);

        return [
            'id' => Uuid::uuid4()->toString(),
            'category_id' => PostCategory::factory(),
            'type' => $this->faker->randomElement(['CollectionPage', 'BreadcrumbList']),
            'title' => $title,
            'slug' => SlugHelper::generatePersianSlug($title, CategorySchema::class, 'slug'),
            'content' => $faker->paragraph(5),
            'description' => $faker->sentence(10),
            'data' => [
                'name' => $faker->sentence(4),
                'description' => $faker->sentence(10),
                'url' => $faker->domain(),
            ],
            'schema_json' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => $faker->sentence(4),
                'description' => $faker->sentence(10),
                'url' => $faker->domain(),
            ],
            'status' => 'published',
            'visibility' => 'public',
            'language' => 'fa',
            'author' => $faker->fullName(),
            'created_by' => User::factory(),
        ];
    }
}
