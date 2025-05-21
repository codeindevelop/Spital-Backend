<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranRuralDistrict;

/**
 * @property int $rural_district_id
 */
trait BelongsToRuralDistrict
{

    public function ruralDistrict()
    {
        return $this->belongsTo(IranRuralDistrict::class, 'rural_district_id');
    }

   
    public function getRuralDistrict()
    {
        return $this->ruralDistrict()->first();
    }
}
