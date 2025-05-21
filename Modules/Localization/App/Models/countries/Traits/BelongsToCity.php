<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranCity;

/**
 * @property int $city_id
 * @method belongsTo(string $class, string $string)
 */
trait BelongsToCity
{

    public function city()
    {
        return $this->belongsTo(IranCity::class, 'city_id');
    }


    public function getCity()
    {
        return $this->city()->first();
    }
}
