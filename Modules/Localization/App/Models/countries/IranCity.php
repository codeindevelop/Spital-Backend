<?php

namespace Modules\Localization\App\Models\countries;



use Illuminate\Database\Eloquent\Model;
use Modules\Localization\App\Models\countries\Traits\BelongsToCounty;
use Modules\Localization\App\Models\countries\Traits\BelongsToProvince;
use Modules\Localization\App\Models\countries\Traits\BelongsToSector;
use Modules\Localization\App\Models\countries\Traits\HasCityDistricts;


/**
 * Class IranCity (Shahr)
 */
class IranCity extends Model
{
    use BelongsToProvince, BelongsToCounty, BelongsToSector, HasCityDistricts;

    protected $table = 'iran_cities';

    protected function getReferenceKey(): string
    {
        return 'city_id';
    }
}
