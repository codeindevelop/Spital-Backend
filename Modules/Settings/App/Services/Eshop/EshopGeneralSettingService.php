<?php

namespace Modules\Settings\App\Services\System\Eshop;


use Modules\Settings\App\Models\Eshop\EshopGeneralSetting;
use Modules\Settings\App\Repositories\System\Eshop\EshopGeneralSettingRepository;

class EshopGeneralSettingService
{
    protected EshopGeneralSettingRepository $settingRepository;

    public function __construct(EshopGeneralSettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Get the general settings.
     *
     * @return EshopGeneralSetting|null
     */
    public function getSettings(): ?EshopGeneralSetting
    {
        return $this->settingRepository->getFirst();
    }

    /**
     * Update or create the general settings.
     *
     * @param  array  $data
     * @return EshopGeneralSetting
     * @throws \Exception
     */
    public function updateSettings(array $data): EshopGeneralSetting
    {
        return $this->settingRepository->updateOrCreate($data);
    }
}
