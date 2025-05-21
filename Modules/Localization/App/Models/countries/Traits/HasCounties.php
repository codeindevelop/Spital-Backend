<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranCounty;

/**
 * @method getReferenceKey()
 * @method hasMany(string $class, $getReferenceKey)
 */
trait HasCounties
{

    public function counties()
    {
        return $this->hasMany(IranCounty::class, $this->getReferenceKey());
    }

    public function getCounties($paginate = false)
    {
        $query = $this->counties()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    public function getActiveCounties($paginate = false)
    {
        $query = $this->counties()->active()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
 
    public function getNotActiveCounties($paginate = false)
    {
        $query = $this->counties()->notActive()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

}
