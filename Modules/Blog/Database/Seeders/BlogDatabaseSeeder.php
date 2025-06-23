<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\App\Models\Setting\Post;
use Modules\Blog\App\Models\Setting\PostCategory;
use Modules\Blog\App\Models\Setting\PostComment;
use Modules\User\App\Models\User;

class BlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // تولید کاربران
        $users = User::factory()->count(5)->create();

        // تولید دسته‌بندی‌ها (10 دسته‌بندی، برخی با والد)
        $categories = PostCategory::factory()->count(5)->create([
            'created_by' => $users->random()->id,
            'updated_by' => $users->random()->id,
        ]);

        // اضافه کردن دسته‌بندی‌های فرزند
        foreach ($categories as $category) {
            PostCategory::factory()->count(2)->create([
                'parent_id' => $category->id,
                'created_by' => $users->random()->id,
                'updated_by' => $users->random()->id,
            ]);
        }

        // تولید سئو و اسکیما برای دسته‌بندی‌ها
//        foreach (PostCategory::all() as $category) {
//            PostCategorySeo::factory()->create([
//                'category_id' => $category->id,
//                'created_by' => $users->random()->id,
//            ]);
//            PostCategorySchema::factory()->create([
//                'category_id' => $category->id,
//                'created_by' => $users->random()->id,
//            ]);
//        }

        // تولید پست‌ها (20 پست)
        $posts = Post::factory()->count(20)->create([
            'author_id' => $users->random()->id,
            'category_id' => $categories->random()->id,
            'created_by' => $users->random()->id,
            'updated_by' => $users->random()->id,
        ]);

        // تولید سئو و اسکیما برای پست‌ها
//        foreach ($posts as $post) {
//            PostSeo::factory()->create([
//                'post_id' => $post->id,
//                'created_by' => $users->random()->id,
//            ]);
//            PostSchema::factory()->create([
//                'post_id' => $post->id,
//                'created_by' => $users->random()->id,
//            ]);
//        }

        // تولید کامنت‌ها (50 کامنت، برخی با والد)
        $comments = PostComment::factory()->count(30)->create([
            'post_id' => $posts->random()->id,
            'user_id' => $users->random()->id,
            'created_by' => $users->random()->id,
            'updated_by' => $users->random()->id,
        ]);

        // اضافه کردن کامنت‌های فرزند
        foreach ($comments as $comment) {
            PostComment::factory()->count(2)->create([
                'post_id' => $comment->post_id,
                'parent_id' => $comment->id,
                'user_id' => $users->random()->id,
                'created_by' => $users->random()->id,
                'updated_by' => $users->random()->id,
            ]);
        }

    }
}
