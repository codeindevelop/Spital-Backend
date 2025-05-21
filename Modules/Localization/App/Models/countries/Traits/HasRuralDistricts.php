<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranRuralDistrict;

/**
 * @method hasMany(string $class, $getReferenceKey)
 * @method getReferenceKey()
 */
trait HasRuralDistricts
{
    public function ruralDistricts()
    {
        return $this->hasMany(IranRuralDistrict::class, $this->getReferenceKey());
    }

    public function getRuralDistricts($paginate = false)
    {
        $query = $this->ruralDistricts()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    public function getActiveRuralDistricts($paginate = false)
    {
        $query = $this->ruralDistricts()->active()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
 
    public function getNotActiveRuralDistricts($paginate = false)
    {
        $query = $this->ruralDistricts()->notActive()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
}
