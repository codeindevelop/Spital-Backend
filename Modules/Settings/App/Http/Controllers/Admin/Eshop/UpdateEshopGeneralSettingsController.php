<?php

namespace Modules\Settings\App\Http\Controllers\Admin\Eshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Modules\Settings\App\Services\Eshop\EshopGeneralSettingService;
use Symfony\Component\HttpFoundation\Response;

class UpdateEshopGeneralSettingsController extends Controller
{
    protected EshopGeneralSettingService $settingService;

    public function __construct(EshopGeneralSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Update the e-shop general settings.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!Auth::user()->can('setting:eshop:update')) {
            return response()->json(['error' => 'شما اجازه به‌روزرسانی تنظیمات فروشگاه را ندارید.'],
                Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'store_logo' => ['nullable', 'string', 'url'], // فرضاً لوگو به‌صورت URL
            'store_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'mobile_number' => ['nullable', 'string', 'max:20'],
            'purchase_mode' => ['nullable', 'in:guest,registered'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'sale_scope' => ['nullable', 'in:worldwide,iran,province,city'],
            'shipping_scope' => ['nullable', 'in:worldwide,iran,province,city'],
            'tax_enabled' => ['nullable', 'boolean'],
            'coupon_enabled' => ['nullable', 'boolean'],
            'currency' => ['nullable', 'string', 'max:3'], // مثلاً USD, IRR
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $settings = $this->settingService->updateSettings($request->all());
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
                    'message' => 'تنظیمات فروشگاه با موفقیت به‌روزرسانی شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to update e-shop settings: '.$e->getMessage(), [
                'request' => $request->all(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در به‌روزرسانی تنظیمات رخ داد.'],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
