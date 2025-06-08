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
            \Log::warning('Permission denied for getSettings', ['user_id' => Auth::id()]);
            return response()->json(['error' => 'شما اجازه مشاهده تنظیمات را ندارید.'], 403);
        }

        $settings = $this->service->getSettings();
        \Log::info('Settings retrieved', ['settings' => $settings]);

        return response()->json([
            'data' => [
                'settings' => $settings,
            ],
        ], Response::HTTP_OK);
    }

    public function updateSettings(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('settings:seo:update')) {
            \Log::warning('Permission denied for updateSettings', ['user_id' => Auth::id()]);
            return response()->json(['error' => 'شما اجازه ویرایش تنظیمات را ندارید.'], 403);
        }

        \Log::info('Update SEO Settings Request:', [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'input' => $request->all(),
            'files' => $request->files->all(),
            'content_type' => $request->header('Content-Type'),
            'user_id' => Auth::id(),
        ]);

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
            \Log::error('Validation failed:', ['errors' => $validator->errors()->toArray()]);
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
            \Log::info('og_image present in request', ['og_image' => $request->input('og_image')]);
            if ($request->hasFile('og_image') && $request->file('og_image')->isValid()) {
                $data['og_image'] = $request->file('og_image');
                \Log::info('Valid og_image file detected', [
                    'filename' => $data['og_image']->getClientOriginalName(),
                    'size' => $data['og_image']->getSize(),
                ]);
            } elseif (in_array($request->input('og_image'), ['', 'null', 'empty'])) {
                $data['og_image'] = null;
                \Log::info('Request to delete og_image detected', ['og_image' => $request->input('og_image')]);
            } else {
                \Log::warning('Unexpected og_image value', ['og_image' => $request->input('og_image')]);
            }
        } else {
            \Log::info('No og_image provided in request');
        }

        \Log::info('Data prepared for update:', ['data' => $data]);

        try {
            $settings = $this->service->updateSettings($data, Auth::user()->id);
            \Log::info('SEO settings updated successfully', ['settings' => $settings]);
            return response()->json([
                'data' => [
                    'settings' => $settings,
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error updating SEO settings: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'خطا در به‌روزرسانی تنظیمات: '.$e->getMessage()], 500);
        }
    }
}
