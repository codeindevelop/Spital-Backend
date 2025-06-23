<?php

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Blog\App\Models\Setting\PostCategory;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;
use Ybazli\Faker\Faker;

class PostCategoryFactory extends Factory
{
    protected $model = PostCategory::class;

    public function definition(): array
    {
        $faker = new Faker();
        $name = $faker->word();

        return [
            'id' => Uuid::uuid4()->toString(),
            'parent_id' => null, // برای سادگی، والد رو null می‌ذاریم (می‌تونی بعداً تغییر بدی)
            'name' => $name,
            'slug' => SlugHelper::generatePersianSlug($name, PostCategory::class, 'slug'),
            'description' => $faker->sentence(10),
            'is_active' => true,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
