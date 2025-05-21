<?php

namespace Modules\Localization\App\Models\countries;

use Illuminate\Database\Eloquent\Model;
use Modules\Localization\App\Models\countries\Traits\BelongsToCity;
use Modules\Localization\App\Models\countries\Traits\BelongsToCounty;
use Modules\Localization\App\Models\countries\Traits\BelongsToProvince;
use Modules\Localization\App\Models\countries\Traits\BelongsToSector;


/**
 * Class IranCityDistrict (Mantaghe Shahri)
 */
class IranCityDistrict extends Model
{
    use BelongsToProvince, BelongsToCounty, BelongsToSector, BelongsToCity;

    protected $table = 'iran_city_districts';

    protected function getReferenceKey(): string
    {
        return 'city_district_id';
    }
}
