<?php

namespace Modules\Seo\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Seo\App\Models\post\PostSchema;
use Modules\Blog\App\Models\Setting\Post;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;
use Ybazli\Faker\Faker;

class PostSchemaFactory extends Factory
{
    protected $model = PostSchema::class;

    public function definition(): array
    {
        $faker = new Faker();
        $title = $faker->sentence(4);

        return [
            'id' => Uuid::uuid4()->toString(),
            'post_id' => Post::factory(),
            'type' => $this->faker->randomElement(['Article', 'FAQPage']),
            'title' => $title,
            'slug' => SlugHelper::generatePersianSlug($title, PostSchema::class, 'slug'),
            'content' => $faker->paragraph(5),
            'description' => $faker->sentence(10),
            'data' => [
                'headline' => $faker->sentence(4),
                'author' => $faker->firstName(),
                'datePublished' => $this->faker->date(),
            ],
            'schema_json' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $faker->sentence(4),
                'description' => $faker->sentence(10),
                'author' => $faker->fullName(),
                'datePublished' => $this->faker->date(),
            ],
            'status' => 'published',
            'visibility' => 'public',
            'language' => 'fa',
            'author' => $faker->fullName(),
            'created_by' => User::factory(),
        ];
    }
}
