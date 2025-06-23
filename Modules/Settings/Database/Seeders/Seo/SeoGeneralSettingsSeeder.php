<?php

namespace Modules\Settings\Database\Seeders\Seo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Seo\App\Models\Setting\SeoGeneralSetting;

class SeoGeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        SeoGeneralSetting::create([
            'id' => (string) Str::ulid(),
            'site_name' => 'My Site',
            'site_alternative_name' => 'My Site Alternative',
            'site_slogan' => 'Welcome to Our Awesome Site!',
            'og_image' => 'https://abrecode.com/images/default-og.jpg',
            'title_separator' => '-',
        ]);
    }
}
