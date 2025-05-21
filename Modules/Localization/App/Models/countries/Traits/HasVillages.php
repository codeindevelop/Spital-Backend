<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranVillage;

/**
 * @method getReferenceKey()
 * @method hasMany(string $class, $getReferenceKey)
 */
trait HasVillages
{

    public function villages()
    {
        return $this->hasMany(IranVillage::class, $this->getReferenceKey());
    }


    public function getVillages($paginate = false)
    {
        $query = $this->villages()->orderBy('id','ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    public function getActiveVillages($paginate = false)
    {
        $query = $this->villages()->active()->orderBy('id','ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    public function getNotActiveVillages($paginate = false)
    {
        $query = $this->villages()->notActive()->orderBy('id','ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
}
