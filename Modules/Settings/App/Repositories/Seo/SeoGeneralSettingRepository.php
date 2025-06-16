<?php

namespace Modules\Settings\App\Repositories\Eshop\Seo;

use Illuminate\Support\Facades\Storage;
use Modules\Settings\App\Models\Eshop\Seo\SeoGeneralSetting;

class SeoGeneralSettingRepository
{
    public function getSettings(): SeoGeneralSetting
    {
        $settings = SeoGeneralSetting::firstOrFail();
        \Log::info('Settings retrieved from database', ['settings' => $settings->toArray()]);
        return $settings;
    }

    public function updateSettings(array $data, ?string $userId = null): SeoGeneralSetting
    {
        $settings = SeoGeneralSetting::firstOrFail();
        \Log::info('Preparing to update settings', ['data' => $data, 'user_id' => $userId]);

        $updateData = array_filter([
            'site_name' => $data['site_name'] ?? $settings->site_name,
            'site_alternative_name' => $data['site_alternative_name'] ?? $settings->site_alternative_name,
            'site_slogan' => $data['site_slogan'] ?? $settings->site_slogan,
            'title_separator' => $data['title_separator'] ?? $settings->title_separator,
        ]);

        // مدیریت og_image
        if (array_key_exists('og_image', $data)) {
            \Log::info('Processing og_image', ['og_image' => $data['og_image']]);
            if ($data['og_image'] instanceof \Illuminate\Http\UploadedFile) {
                \Log::info('Uploading new og_image', ['filename' => $data['og_image']->getClientOriginalName()]);
                // حذف فایل قبلی اگه وجود داره
                if ($settings->og_image) {
                    $oldPath = 'settings/seo/general/'.$settings->og_image;
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                        \Log::info('Deleted old og_image', ['path' => $oldPath]);
                    } else {
                        \Log::warning('Old og_image not found for deletion', ['path' => $oldPath]);
                    }
                }
                // آپلود فایل جدید
                $extension = $data['og_image']->getClientOriginalExtension();
                $filename = 'generalImage.'.$extension;
                $path = $data['og_image']->storeAs('settings/seo/general', $filename, 'public');
                $updateData['og_image'] = $filename;
                \Log::info('Uploaded new og_image', ['path' => $path, 'filename' => $filename]);
            } elseif ($data['og_image'] === null) {
                \Log::info('Attempting to delete og_image');
                if ($settings->og_image) {
                    $oldPath = 'settings/seo/general/'.$settings->og_image;
                    \Log::info('Checking if og_image exists', ['path' => $oldPath]);
                    if (Storage::disk('public')->exists($oldPath)) {
                        \Log::info('Deleting og_image file', ['path' => $oldPath]);
                        Storage::disk('public')->delete($oldPath);
                        \Log::info('Deleted og_image file', ['path' => $oldPath]);
                    } else {
                        \Log::warning('og_image file not found for deletion', ['path' => $oldPath]);
                    }
                    $updateData['og_image'] = null;
                    \Log::info('Set og_image to null in database');
                } else {
                    \Log::info('No og_image to delete in database');
                }
            } else {
                \Log::warning('Unexpected og_image value in repository', ['og_image' => $data['og_image']]);
            }
        } else {
            \Log::info('No og_image provided, preserving existing image');
        }

        // آپدیت دیتابیس
        \Log::info('Updating database with data', ['data' => $updateData]);
        $updated = $settings->update($updateData);
        \Log::info('Database update result', ['updated' => $updated, 'data' => $updateData]);

        if (!$updated) {
            \Log::error('Failed to update settings in database', ['data' => $updateData]);
            throw new \Exception('آپدیت تنظیمات در دیتابیس انجام نشد.');
        }

        $settings->refresh();
        \Log::info('Settings updated successfully', ['settings' => $settings->toArray()]);

        return $settings;
    }
}
