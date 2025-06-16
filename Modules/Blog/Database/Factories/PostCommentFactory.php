<?php

namespace Modules\Blog\Database\Factories;

use Modules\Settings\App\Models\System\Blog\PostComment;
use Modules\Settings\App\Models\System\Blog\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;
use Ybazli\Faker\Faker;

class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;

    public function definition(): array
    {
        $faker = new Faker();

        return [
            'id' => Uuid::uuid4()->toString(),
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'parent_id' => null, // برای سادگی، بدون والد (می‌تونی بعداً سلسله‌مراتبی کنی)
            'author_email' => $faker->email(),
            'author_url' => $faker->word(),
            'author_ip' => $this->faker->ipv4,
            'content' => $faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'spam']),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
