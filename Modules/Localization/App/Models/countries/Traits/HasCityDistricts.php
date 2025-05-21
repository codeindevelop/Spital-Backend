<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranCityDistrict;

trait HasCityDistricts
{

    public function cityDistricts()
    {
        return $this->hasMany(IranCityDistrict::class, $this->getReferenceKey());
    }

    public function getCityDistricts($paginate = false)
    {
        $query = $this->cityDistricts()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    public function getActiveCityDistricts($paginate = false)
    {
        $query = $this->cityDistricts()->active()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
 
    public function getNotActiveCityDistricts($paginate = false)
    {
        $query = $this->cityDistricts()->notActive()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
}
