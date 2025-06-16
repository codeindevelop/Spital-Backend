<?php

namespace Modules\Settings\App\Http\Controllers\Admin\Eshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use Modules\Settings\App\Services\Eshop\EshopGeneralSettingService;
use Symfony\Component\HttpFoundation\Response;

class GetEshopGeneralSettingsController extends Controller
{
    protected EshopGeneralSettingService $settingService;

    public function __construct(EshopGeneralSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Retrieve the e-shop general settings.
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $settings = $this->settingService->getSettings();

        if (!$settings) {
            return response()->json(['error' => 'تنظیمات یافت نشد.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => [
                'settings' => [
                    'id' => $settings->id,
                    'store_logo' => $settings->store_logo,
                    'store_name' => $settings->store_name,
                    'email' => $settings->email,
                    'phone_number' => $settings->phone_number,
                    'mobile_number' => $settings->mobile_number,
                    'purchase_mode' => $settings->purchase_mode,
                    'address' => $settings->address,
                    'city' => $settings->city,
                    'country' => $settings->country,
                    'province' => $settings->province,
                    'postal_code' => $settings->postal_code,
                    'sale_scope' => $settings->sale_scope,
                    'shipping_scope' => $settings->shipping_scope,
                    'tax_enabled' => $settings->tax_enabled,
                    'coupon_enabled' => $settings->coupon_enabled,
                    'currency' => $settings->currency,
                ],
            ],
        ], Response::HTTP_OK);
    }
}
