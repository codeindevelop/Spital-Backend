<?php

namespace Modules\Settings\App\Http\Controllers\Admin\System\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Settings\App\Services\System\GeneralSettingService;
use Modules\User\App\Models\User;

class GeneralSettingController extends Controller
{
    protected GeneralSettingService $service;

    public function __construct(GeneralSettingService $service)
    {
        $this->service = $service;
    }

    /**
     * Get general settings
     */
    public function show(): JsonResponse
    {
        $settings = $this->service->getSettings();

        if (!$settings) {
            return response()->json(['message' => 'Settings not found'], 404);
        }

        return response()->json([
            'data' => $settings,
            'message' => 'Settings retrieved successfully',
        ]);
    }

    /**
     * Update general settings
     */
    public function update(Request $request): JsonResponse
    {

        $user = Auth::user();
        if (!$user->can('settings:system:update')) {
            activity()
                ->causedBy($user)
                ->withProperties(['user_id' => $user ? $user->id : null])
                ->log('تلاش ناموفق برای به‌روزرسانی تنظیمات کلی به دلیل عدم دسترسی');
            return response()->json(['error' => 'شما اجازه ویرایش تنظیمات را ندارید.'], 403);
        }


        $settings = $this->service->updateSettings($request->all());

        return response()->json([
            'data' => $settings,
            'message' => 'Settings updated successfully',
        ]);
    }
}
