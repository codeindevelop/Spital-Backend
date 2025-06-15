<?php

namespace Modules\Settings\App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\Services\Blog\BlogSettingService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminBlogSettingController extends Controller
{
    protected BlogSettingService $settingService;

    public function __construct(BlogSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function getSettings(): JsonResponse
    {
        if (!Auth::user()->can('settings:blog:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده تنظیمات را ندارید.'], 403);
        }

        $settings = $this->settingService->getSettings();
        return response()->json([
            'data' => ['settings' => $settings],
        ], Response::HTTP_OK);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        if (!Auth::user()->can('settings:blog:update')) {
            return response()->json(['error' => 'شما اجازه ویرایش تنظیمات را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'public_posts_per_page' => ['integer', 'min:1', 'max:100'],
            'admin_posts_per_page' => ['integer', 'min:1', 'max:100'],
            'font_family' => ['string', 'max:255'],
            'default_cover_image' => ['nullable', 'url', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $settings = $this->settingService->updateSettings($request->all());
            return response()->json([
                'data' => [
                    'settings' => $settings,
                    'message' => 'تنظیمات با موفقیت به‌روزرسانی شد.',
                ],
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Failed to update settings: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در به‌روزرسانی تنظیمات رخ داد.'], 500);
        }
    }
}
