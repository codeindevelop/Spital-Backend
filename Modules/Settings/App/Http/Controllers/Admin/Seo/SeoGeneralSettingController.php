<?php

namespace Modules\Settings\App\Http\Controllers\Admin\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Modules\Settings\App\Services\Seo\SeoGeneralSettingService;
use Symfony\Component\HttpFoundation\Response;

class SeoGeneralSettingController extends Controller
{
    protected SeoGeneralSettingService $service;

    public function __construct(SeoGeneralSettingService $service)
    {
        $this->service = $service;
    }

    public function getSettings(): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('settings:seo:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده تنظیمات را ندارید.'], 403);
        }

        $settings = $this->service->getSettings();

        return response()->json([
            'data' => [
                'settings' => $settings,
            ],
        ], Response::HTTP_OK);
    }

    public function updateSettings(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('settings:seo:update')) {
            return response()->json(['error' => 'شما اجازه ویرایش تنظیمات را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'site_name' => ['string', 'max:255'],
            'site_alternative_name' => ['string', 'max:255', 'nullable'],
            'site_slogan' => ['string', 'max:255', 'nullable'],
            'og_image' => ['string', 'url', 'max:255', 'nullable'],
            'title_separator' => ['string', 'max:5', 'in:-,|'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $settings = $this->service->updateSettings($request->all());

        return response()->json([
            'data' => [
                'settings' => $settings,
            ],
        ], Response::HTTP_OK);
    }
}
