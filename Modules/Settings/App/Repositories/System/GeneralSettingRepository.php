<?php

namespace Modules\Settings\App\Repositories\System;


use Modules\Settings\App\Models\System\GeneralSetting;

class GeneralSettingRepository
{
    /**
     * Get the first general setting record
     */
    public function getFirst()
    {
        return GeneralSetting::with(['timezone', 'language', 'country'])->first();
    }

    /**
     * Update or create general setting
     */
    public function updateOrCreate(array $data)
    {
        return GeneralSetting::updateOrCreate(
            ['id' => GeneralSetting::first()->id ?? null],
            $data
        );
    }
}
