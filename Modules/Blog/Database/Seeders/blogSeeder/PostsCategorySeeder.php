<?php

namespace Modules\Blog\Database\Seeders\blogSeeder;

use Carbon\Carbon;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('post_categories')->insert([
            "id" => 1,
            "parent_id" => null,
            "category_name" => "دسته بندی نشده",
            "category_link_slug" => "uncategorized",
            "active" => true,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now()
        ]);
    }
}
