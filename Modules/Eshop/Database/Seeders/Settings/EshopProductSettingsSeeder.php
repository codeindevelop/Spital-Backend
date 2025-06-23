<?php

namespace Modules\Eshop\Database\Seeders\Settings;

use Illuminate\Database\Seeder;
use Modules\Eshop\App\Models\Settings\Product\EshopProductSetting;
use Ramsey\Uuid\Uuid;

class EshopProductSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EshopProductSetting::updateOrCreate(
            ['id' => Uuid::uuid4()->toString()],
            [
                'redirect_to_cart' => true,
                'dynamic_cart' => true,
                'placeholder_image' => 'default-placeholder.png',
                'weight_unit' => 'KG',
                'dimensions_unit' => 'cm',
                'product_reviews' => true,
                'only_owners_can_reviews' => true,
                'show_verified' => true,
                'star_rating_review' => true,
                'star_rating_review_required' => false,
                'manage_stock' => true,
                'hold_stock' => '60',
                'low_stock_notification' => true,
                'out_of_stock_notification' => true,
                'low_stock_threshold' => '2',
                'out_of_stock_threshold' => '2',
                'out_of_stock_visibility' => false,
            ]
        );
    }
}
