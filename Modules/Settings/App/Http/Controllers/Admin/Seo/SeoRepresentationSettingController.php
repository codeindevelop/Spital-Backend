<?php

namespace Modules\Settings\App\Http\Controllers\Admin\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\Services\Seo\SeoRepresentationSettingService;
use Symfony\Component\HttpFoundation\Response;

class SeoRepresentationSettingController extends Controller
{
    protected SeoRepresentationSettingService $service;

    public function __construct(SeoRepresentationSettingService $service)
    {
        $this->service = $service;
    }

    public function getSettings(): JsonResponse
    {
        $user = Auth::user();
        if (!$user->can('settings:seo:view')) {
            activity()
                ->causedBy($user)
                ->withProperties(['user_id' => $user ? $user->id : null])
                ->log('تلاش ناموفق برای مشاهده تنظیمات نمایندگی SEO به دلیل عدم دسترسی');
            return response()->json(['error' => 'شما اجازه مشاهده تنظیمات را ندارید.'], 403);
        }

        $settings = $this->service->getSettings();

        activity()
            ->performedOn($settings)
            ->causedBy($user)
            ->withProperties(['settings' => $settings])
            ->log('مشاهده تنظیمات نمایندگی SEO');

        return response()->json([
            'data' => [
                'settings' => $settings,
            ],
        ], Response::HTTP_OK);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->can('settings:seo:update')) {
            activity()
                ->causedBy($user)
                ->withProperties(['user_id' => $user ? $user->id : null])
                ->log('تلاش ناموفق برای به‌روزرسانی تنظیمات نمایندگی SEO به دلیل عدم دسترسی');
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
            ->log('درخواست به‌روزرسانی تنظیمات نمایندگی SEO');

        $validator = Validator::make($request->all(), [
            'site_type' => ['required', 'string', 'in:personal,company'],
            'company_name' => ['required_if:site_type,company', 'string', 'max:255'],
            'company_alternative_name' => ['nullable', 'string', 'max:255'],
            'company_logo' => [
                'sometimes',
                'nullable',
                function ($attribute, $value, $fail) {
                    if (in_array($value, ['', 'null', 'empty'])) {
                        return true; // اجازه حذف لوگو
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
                        $fail('فیلد company_logo باید یک تصویر یا null/empty باشد.');
                    }
                },
            ],
            'company_description' => ['nullable', 'string'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:20'],
            'company_legal_name' => ['nullable', 'string', 'max:255'],
            'company_founded_date' => ['nullable', 'date'],
            'company_employee_count' => ['nullable', 'integer', 'min:0'],
            'company_branch_count' => ['nullable', 'integer', 'min:0'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_ceo' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_telegram' => ['nullable', 'url', 'max:255'],
            'social_tiktok' => ['nullable', 'url', 'max:255'],
            'social_snapchat' => ['nullable', 'url', 'max:255'],
            'social_threads' => ['nullable', 'url', 'max:255'],
            'social_github' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_pinterest' => ['nullable', 'url', 'max:255'],
            'social_wikipedia' => ['nullable', 'url', 'max:255'],
        ]);

        if ($validator->fails()) {
            activity()
                ->causedBy($user)
                ->withProperties(['errors' => $validator->errors()->toArray()])
                ->log('خطای اعتبارسنجی در به‌روزرسانی تنظیمات نمایندگی SEO');
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $settings = $this->service->updateSettings($request->all(), $user->id);
            activity()
                ->performedOn($settings)
                ->causedBy($user)
                ->withProperties(['settings' => $settings])
                ->log('تنظیمات نمایندگی SEO با موفقیت به‌روزرسانی شد');
            return response()->json([
                'data' => [
                    'settings' => $settings->toArray(),
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            activity()
                ->causedBy($user)
                ->withProperties(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()])
                ->log('خطا در به‌روزرسانی تنظیمات نمایندگی SEO');
            return response()->json(['error' => 'خطا در به‌روزرسانی تنظیمات: '.$e->getMessage()], 500);
        }
    }
}
