<?php

namespace Modules\Settings\App\Http\Controllers\Admin\System\General\Admin\Seo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Files\App\Http\Controllers\Controller;
use Modules\Settings\App\Services\System\Seo\SeoGeneralSettingService;
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
        $user = Auth::guard('api')->user();
        if (!$user->can('settings:seo:view')) {
            activity()
                ->causedBy($user)
                ->withProperties(['user_id' => $user ? $user->id : null])
                ->log('تلاش ناموفق برای مشاهده تنظیمات SEO به دلیل عدم دسترسی');
            return response()->json(['error' => 'شما اجازه مشاهده تنظیمات را ندارید.'], 403);
        }

        $settings = $this->service->getSettings(); // انتظار مدل SeoGeneralSetting

        activity()
            ->causedBy($user)
            ->withProperties(['settings' => $settings])
            ->log('مشاهده تنظیمات SEO');

        return response()->json([
            'data' => [
                'settings' => $settings, // تبدیل به آرایه برای پاسخ API
            ],
        ], Response::HTTP_OK);
    }

    public function updateSettings(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('api')->user();
        if (!$user->can('settings:seo:update')) {
            activity()
                ->causedBy($user)
                ->withProperties(['user_id' => $user ? $user->id : null])
                ->log('تلاش ناموفق برای به‌روزرسانی تنظیمات SEO به دلیل عدم دسترسی');
            return response()->json(['error' => 'شما اجازه ویرایش تنظیمات را ندارید.'], 403);
        }

        activity()
            ->causedBy($user)
            ->withProperties([
                'method' => $request->method(),
                'input' => $request->all(),
                'files' => array_keys($request->files->all()),
                'content_type' => $request->header('Content-Type'),
            ])
            ->log('درخواست به‌روزرسانی تنظیمات SEO');

        // اعتبارسنجی
        $validator = Validator::make($request->all(), [
            'site_name' => ['string', 'max:255'],
            'site_alternative_name' => ['string', 'max:255', 'nullable'],
            'site_slogan' => ['string', 'max:255', 'nullable'],
            'title_separator' => ['string', 'max:5', 'in:-,|,–,—,:,·,•,*,⋆,~,«,»,>,<'],
            'og_image' => [
                'sometimes',
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === '' || $value === 'null' || $value === 'empty') {
                        return true; // اجازه حذف تصویر
                    }
                    if (is_file($value)) {
                        $imageValidator = Validator::make(
                            [$attribute => $value],
                            [$attribute => ['image', 'mimes:jpeg,png,jpg', 'max:2048']]
                        );
                        if ($imageValidator->fails()) {
                            $fail($imageValidator->errors()->first($attribute));
                        }
                    } else {
                        $fail('فیلد og_image باید یک تصویر یا null/empty باشد.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            activity()
                ->causedBy($user)
                ->withProperties(['errors' => $validator->errors()->toArray()])
                ->log('خطای اعتبارسنجی در به‌روزرسانی تنظیمات SEO');
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // استخراج داده‌ها
        $data = $request->only([
            'site_name',
            'site_alternative_name',
            'site_slogan',
            'title_separator',
        ]);

        // مدیریت og_image
        if ($request->has('og_image')) {
            if ($request->hasFile('og_image') && $request->file('og_image')->isValid()) {
                $data['og_image'] = $request->file('og_image');
                activity()
                    ->causedBy($user)
                    ->withProperties([
                        'filename' => $data['og_image']->getClientOriginalName(),
                        'size' => $data['og_image']->getSize(),
                    ])
                    ->log('آپلود تصویر جدید برای og_image');
            } elseif (in_array($request->input('og_image'), ['', 'null', 'empty'])) {
                $data['og_image'] = null;
                activity()
                    ->causedBy($user)
                    ->withProperties(['og_image' => $request->input('og_image')])
                    ->log('درخواست حذف تصویر og_image');
            } else {
                activity()
                    ->causedBy($user)
                    ->withProperties(['og_image' => $request->input('og_image')])
                    ->log('مقدار غیرمنتظره برای og_image');
            }
        } else {
            activity()
                ->causedBy($user)
                ->log('هیچ تصویری برای og_image ارائه نشده، حفظ تصویر فعلی');
        }

        try {
            $settings = $this->service->updateSettings($data, $user->id);
            activity()
                ->causedBy($user)
                ->withProperties(['settings' => $settings])
                ->log('تنظیمات SEO با موفقیت به‌روزرسانی شد');
            return response()->json([
                'data' => [
                    'settings' => $settings,
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            activity()
                ->causedBy($user)
                ->withProperties(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()])
                ->log('خطا در به‌روزرسانی تنظیمات SEO');
            return response()->json(['error' => 'خطا در به‌روزرسانی تنظیمات: '.$e->getMessage()], 500);
        }
    }
}
