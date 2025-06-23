<?php

namespace Modules\Eshop\App\Http\Controllers\Admin\Settings\Product;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Eshop\App\Services\Settings\Product\EshopProductSettingService;
use Modules\Eshop\App\Http\Resources\Settings\Product\EshopProductSettingResource;

class GetEshopProductSettingController extends Controller
{
    protected EshopProductSettingService $service;

    public function __construct(EshopProductSettingService $service)
    {
        $this->service = $service;
    }

    /**
     * Get product settings.
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $settings = $this->service->getSettings();

        if (!$settings) {
            return response()->json(['message' => 'تنظیمات یافت نشد.'], 404);
        }

        return response()->json(new EshopProductSettingResource($settings));
    }
}
