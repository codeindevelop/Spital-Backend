<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranSector;

/**
 * @property int $sector_id
 */
trait BelongsToSector
{

    public function sector()
    {
        return $this->belongsTo(IranSector::class, 'sector_id');
    }

   
    public function getSector()
    {
        return $this->sector()->first();
    }
}
