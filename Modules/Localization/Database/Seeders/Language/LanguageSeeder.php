<?php

namespace Modules\Localization\Database\Seeders\Language;

use Illuminate\Database\Seeder;

use Modules\Localization\App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'fa', 'name' => 'Persian'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'it', 'name' => 'Italian'],
            ['code' => 'ja', 'name' => 'Japanese'],
            ['code' => 'ko', 'name' => 'Korean'],
            ['code' => 'zh', 'name' => 'Chinese'],
            ['code' => 'ar', 'name' => 'Arabic'],
            ['code' => 'ru', 'name' => 'Russian'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
