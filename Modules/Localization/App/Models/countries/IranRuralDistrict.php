<?php

namespace Modules\Localization\App\Models\countries;

use Illuminate\Database\Eloquent\Model;
use Modules\Localization\App\Models\countries\Traits\BelongsToCounty;
use Modules\Localization\App\Models\countries\Traits\BelongsToProvince;
use Modules\Localization\App\Models\countries\Traits\BelongsToSector;
use Modules\Localization\App\Models\countries\Traits\HasVillages;


/**
 * Class IranRuralDistrict (Dehestan)
 */
class IranRuralDistrict extends Model
{
    use BelongsToProvince, BelongsToCounty, BelongsToSector, HasVillages;

    protected $table = 'iran_rural_districts';


    protected function getReferenceKey(): string
    {
        return 'rural_district_id';
    }
}
