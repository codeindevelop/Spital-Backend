<?php

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Blog\App\Models\Setting\Post;
use Modules\Blog\App\Models\Setting\PostCategory;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;
use Ybazli\Faker\Faker;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $faker = new Faker();
        $title = $faker->sentence(6);

        return [
            'id' => Uuid::uuid4()->toString(),
            'author_id' => User::factory(),
            'category_id' => PostCategory::factory(),
            'title' => $title,
            'slug' => SlugHelper::generatePersianSlug($title, Post::class, 'slug'),
            'content' => $faker->paragraph(5),
            'featured_image' => $faker->word(),
            'comment_status' => $this->faker->boolean(80), // 80% احتمال true
            'password' => null,
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'visibility' => $this->faker->randomElement(['public', 'private', 'unlisted']),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_active' => true,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
