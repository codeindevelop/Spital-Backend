<?php

namespace Modules\Localization\App\Models\countries\Traits;


use Modules\Localization\App\Models\countries\IranProvince;

/**
 * @property int $province_id
 */
trait BelongsToProvince
{

    public function province()
    {
        return $this->belongsTo(IranProvince::class, 'province_id');
    }

    
    public function getProvince()
    {
        return $this->province()->first();
    }
}
