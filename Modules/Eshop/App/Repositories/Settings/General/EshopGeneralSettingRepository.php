<?php

namespace Modules\Eshop\App\Repositories\Settings\General;


use Modules\Eshop\App\Models\Settings\General\EshopGeneralSetting;

class EshopGeneralSettingRepository
{
    /**
     * Get the first general setting record.
     *
     * @return EshopGeneralSetting|null
     */
    public function getFirst(): ?EshopGeneralSetting
    {
        return EshopGeneralSetting::first();
    }

    /**
     * Update or create the general setting record.
     *
     * @param  array  $data
     * @return EshopGeneralSetting
     */
    public function updateOrCreate(array $data): EshopGeneralSetting
    {
        return EshopGeneralSetting::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }
}
