<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranSector;

/**
 * @method hasMany(string $class, $getReferenceKey)
 * @method getReferenceKey()
 */
trait HasSectors
{

    public function sectors()
    {
        return $this->hasMany(IranSector::class, $this->getReferenceKey());
    }

    public function getSectors($paginate = false)
    {
        $query = $this->sectors()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    public function getActiveSectors($paginate = false)
    {
        $query = $this->sectors()->active()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
 
    public function getNotActiveSectors($paginate = false)
    {
        $query = $this->sectors()->notActive()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

}
