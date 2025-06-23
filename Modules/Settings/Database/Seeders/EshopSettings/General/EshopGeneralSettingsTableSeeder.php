<?php

namespace Modules\Settings\Database\Seeders\EshopSettings\General;

use Illuminate\Database\Seeder;
use Modules\Eshop\App\Models\Settings\General\EshopGeneralSetting;
use Ramsey\Uuid\Uuid;

class EshopGeneralSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EshopGeneralSetting::updateOrCreate(
            ['id' => Uuid::uuid4()->toString()],
            [
                'store_logo' => 'https://example.com/logo.png',
                'store_name' => null,
                'email' => null,
                'phone_number' => null,
                'mobile_number' => null,
                'purchase_mode' => 'guest',
                'address' => null,
                'city' => null,
                'country' => 'ایران',
                'province' => null,
                'postal_code' => null,
                'sale_scope' => 'iran',
                'shipping_scope' => 'iran',
                'tax_enabled' => true,
                'coupon_enabled' => true,
                'currency' => 'ریال',
            ]
        );
    }
}
