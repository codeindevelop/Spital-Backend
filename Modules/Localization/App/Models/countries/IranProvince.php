<?php

namespace Modules\Localization\App\Models\countries;

use Illuminate\Database\Eloquent\Model;
use Modules\Localization\App\Models\countries\Traits\HasCities;
use Modules\Localization\App\Models\countries\Traits\HasCityDistricts;
use Modules\Localization\App\Models\countries\Traits\HasCounties;
use Modules\Localization\App\Models\countries\Traits\HasRuralDistricts;
use Modules\Localization\App\Models\countries\Traits\HasSectors;
use Modules\Localization\App\Models\countries\Traits\HasVillages;


/**
 * Class IranProvince (Ostan)
 */
class IranProvince extends Model
{
    use HasCounties, HasSectors, HasCities, HasCityDistricts, HasRuralDistricts, HasVillages;

    protected $table = 'iran_provinces';

    protected function getReferenceKey(): string
    {
        return 'province_id';
    }
}
