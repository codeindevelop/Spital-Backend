<?php

namespace Modules\Eshop\App\Repositories\Settings\Product;

use Modules\Eshop\App\Models\Settings\Product\EshopProductSetting;

class EshopProductSettingRepository
{
    protected EshopProductSetting $model;

    public function __construct(EshopProductSetting $model)
    {
        $this->model = $model;
    }

    /**
     * Get the product settings.
     *
     * @return EshopProductSetting|null
     */
    public function getSettings(): ?EshopProductSetting
    {
        return $this->model->first();
    }

    /**
     * Update or create product settings.
     *
     * @param  array  $data
     * @return EshopProductSetting
     */
    public function updateOrCreate(array $data): EshopProductSetting
    {
        return $this->model->updateOrCreate(['id' => $this->model->first()?->id], $data);
    }
}
