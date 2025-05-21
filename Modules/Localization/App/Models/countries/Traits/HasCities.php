<?php

namespace Modules\Localization\App\Models\countries\Traits;



use Modules\Localization\App\Models\countries\IranCity;

/**
 * @method hasMany(string $class, $getReferenceKey)
 * @method getReferenceKey()
 */
trait HasCities
{

    public function cities()
    {
        return $this->hasMany(IranCity::class, $this->getReferenceKey());
    }

    
    public function getCities($paginate = false)
    {
        $query = $this->cities()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    /**
     * @param boolean $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|IranCity[]
     */
    public function getActiveCities($paginate = false)
    {
        $query = $this->cities()->active()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }

    /**
     * @param boolean $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|IranCity[]
     */
    public function getNotActiveCities($paginate = false)
    {
        $query = $this->cities()->notActive()->orderBy('id', 'ASC');

        if ($paginate)
            return $query->paginate();

        return $query->get();
    }
}
