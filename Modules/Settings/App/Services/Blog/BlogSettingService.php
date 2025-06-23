<?php

namespace Modules\Settings\App\Services\System\Blog;


use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Blog\App\Models\Setting\BlogSetting;
use Ramsey\Uuid\Uuid;


class BlogSettingService
{
    public function getSettings(): BlogSetting
    {
        return BlogSetting::firstOrCreate([], [
            'id' => Uuid::uuid4()->toString(),
            'public_posts_per_page' => 15,
            'admin_posts_per_page' => 15,
            'font_family' => 'Vazir',
            'default_cover_image' => null,
        ]);
    }

    /**
     * @throws Exception
     */
    public function updateSettings(array $data): BlogSetting
    {
        try {
            $settings = $this->getSettings();
            $settings->update($data);
            return $settings;
        } catch (Exception $e) {
            Log::error('Error updating blog settings: '.$e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
