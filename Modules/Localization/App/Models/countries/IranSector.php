<?php

namespace Modules\Localization\App\Models\countries;

use Illuminate\Database\Eloquent\Model;
use Modules\Localization\App\Models\countries\Traits\BelongsToCounty;
use Modules\Localization\App\Models\countries\Traits\BelongsToProvince;
use Modules\Localization\App\Models\countries\Traits\HasCities;
use Modules\Localization\App\Models\countries\Traits\HasCityDistricts;
use Modules\Localization\App\Models\countries\Traits\HasRuralDistricts;
use Modules\Localization\App\Models\countries\Traits\HasVillages;


/**
 * Class IranSector (Bakhsh)
 */
class IranSector extends Model
{
    use BelongsToProvince, BelongsToCounty, HasCities, HasCityDistricts, HasRuralDistricts, HasVillages;

    protected $table = 'iran_sectors';


    protected function getReferenceKey(): string
    {
        return 'sector_id';
    }
}
