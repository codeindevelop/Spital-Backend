<?php

namespace Modules\Eshop\App\Http\Controllers\Admin\Settings\Product;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Modules\Eshop\App\Services\Settings\Product\EshopProductSettingService;
use Modules\Eshop\App\Http\Resources\Settings\Product\EshopProductSettingResource;

class UpdateEshopProductSettingController extends Controller
{
    protected EshopProductSettingService $service;

    public function __construct(EshopProductSettingService $service)
    {
        $this->service = $service;
    }

    /**
     * Update product settings.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();

        // بررسی پرمیژن
        if (!$user->can('settings:eshop:update')) {
            activity()
                ->causedBy($user)
                ->withProperties(['user_id' => $user ? $user->id : null])
                ->log('تلاش ناموفق برای به‌روزرسانی تنظیمات محصولات به دلیل عدم دسترسی');
            return response()->json(['error' => 'شما اجازه به‌روزرسانی تنظیمات را ندارید.'], 403);
        }

        // اعتبارسنجی داده‌ها
        $validated = $request->validate([
            'redirect_to_cart' => 'boolean',
            'dynamic_cart' => 'boolean',
            'placeholder_image' => 'nullable|string|max:255',
            'weight_unit' => 'nullable|string|in:KG,G,lbs,os',
            'dimensions_unit' => 'nullable|string|in:m,cm,mm,in,yd',
            'product_reviews' => 'boolean',
            'only_owners_can_reviews' => 'boolean',
            'show_verified' => 'boolean',
            'star_rating_review' => 'boolean',
            'star_rating_review_required' => 'boolean',
            'manage_stock' => 'boolean',
            'hold_stock' => 'nullable|string|max:255',
            'low_stock_notification' => 'boolean',
            'out_of_stock_notification' => 'boolean',
            'low_stock_threshold' => 'nullable|string|max:255',
            'out_of_stock_threshold' => 'nullable|string|max:255',
            'out_of_stock_visibility' => 'boolean',
        ]);

        // به‌روزرسانی تنظیمات
        $settings = $this->service->updateSettings($validated);

        // ثبت لاگ
        activity()
            ->causedBy($user)
            ->performedOn($settings)
            ->log('تنظیمات محصولات با موفقیت به‌روزرسانی شد.');

        return response()->json([
            'message' => 'تنظیمات با موفقیت به‌روزرسانی شد.',
            'data' => new EshopProductSettingResource($settings),
        ]);
    }
}
