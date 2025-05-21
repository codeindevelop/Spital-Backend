<?php

namespace Modules\Localization\App\Models\countries;

use Illuminate\Database\Eloquent\Model;
use Modules\Localization\App\Models\countries\Traits\BelongsToCounty;
use Modules\Localization\App\Models\countries\Traits\BelongsToProvince;
use Modules\Localization\App\Models\countries\Traits\BelongsToRuralDistrict;
use Modules\Localization\App\Models\countries\Traits\BelongsToSector;


/**
 * Class IranVillage (Abadi)
 */
class IranVillage extends Model
{
    use BelongsToProvince, BelongsToCounty, BelongsToSector, BelongsToRuralDistrict;

    protected $table = 'iran_villages';


    protected function getReferenceKey(): string
    {
        return 'village_id';
    }
}
