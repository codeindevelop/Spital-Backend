<?php

namespace Modules\Localization\App\Models\countries;

use Illuminate\Database\Eloquent\Model;
use Modules\Localization\App\Models\countries\Traits\BelongsToProvince;
use Modules\Localization\App\Models\countries\Traits\HasCities;
use Modules\Localization\App\Models\countries\Traits\HasCityDistricts;
use Modules\Localization\App\Models\countries\Traits\HasRuralDistricts;
use Modules\Localization\App\Models\countries\Traits\HasSectors;
use Modules\Localization\App\Models\countries\Traits\HasVillages;


/**
 * Class IranCounty (Shahrestan)
 */
class IranCounty extends Model
{
    use BelongsToProvince, HasSectors, HasCities, HasCityDistricts, HasRuralDistricts, HasVillages;

    protected $table = 'iran_counties';

    protected function getReferenceKey(): string
    {
        return 'county_id';
    }
}
