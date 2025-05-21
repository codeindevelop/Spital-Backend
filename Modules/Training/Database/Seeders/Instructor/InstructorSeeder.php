<?php

namespace Modules\Training\Database\Seeders\Instructor;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('instructors')->insert([
            "id" => 1,
            "user_id" => 666,
            "instructor_slug" => 'yamata',
            "skills" => 'senior software engineer',
            "short_biography" => 'برنامه نویس فول استک و مدیر تیم برنامه نویسی باکسیکد',

            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now()
        ]);
    }
}
