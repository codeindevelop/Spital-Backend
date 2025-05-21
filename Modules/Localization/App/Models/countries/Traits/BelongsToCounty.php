<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranCounty;

/**
 * @property int $county_id
 */
trait BelongsToCounty
{

    public function county()
    {
        return $this->belongsTo(IranCounty::class, 'county_id');
    }

    
    public function getCounty()
    {
        return $this->county()->first();
    }
}
