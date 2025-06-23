<?php

namespace Modules\Eshop\App\Services\Settings\Product;

use Modules\Eshop\App\Repositories\Settings\Product\EshopProductSettingRepository;
use Modules\Eshop\App\Models\Settings\Product\EshopProductSetting;

class EshopProductSettingService
{
    protected EshopProductSettingRepository $repository;

    public function __construct(EshopProductSettingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get product settings.
     *
     * @return EshopProductSetting|null
     */
    public function getSettings(): ?EshopProductSetting
    {
        return $this->repository->getSettings();
    }

    /**
     * Update product settings.
     *
     * @param  array  $data
     * @return EshopProductSetting
     */
    public function updateSettings(array $data): EshopProductSetting
    {
        return $this->repository->updateOrCreate($data);
    }
}
