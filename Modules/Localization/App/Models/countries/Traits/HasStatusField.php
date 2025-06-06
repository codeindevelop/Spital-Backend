<?php
namespace Modules\Localization\App\Models\countries\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property boolean $status
 * @method static Builder active() gets records where status is 1
 * @method static Builder notActive() gets records where status is 0
 * Trait HasStatusField
 */
trait HasStatusField
{

    public function scopeActive(Builder $query)
    {
        return $this->statusQuery($query, 1);
    }


    public function scopeNotActive(Builder $query)
    {
        return $this->statusQuery($query, 0);
    }

    private function statusQuery(Builder $query, $status)
    {
        $query->where('status', $status);

        if (method_exists($this, 'parent')) {
            $query->whereHas('parent', function ($q) use ($status) {
                return $q->where('status', $status);
            });
        }

        if (method_exists($this, 'province')) {
            $query->whereHas('province', function ($q) use ($status) {
                return $q->where('status', $status);
            });
        }

        if (method_exists($this, 'county')) {
            $query->whereHas('county', function ($q) use ($status) {
                return $q->where('status', $status);
            });
        }

        if (method_exists($this, 'sector')) {
            $query->whereHas('sector', function ($q) use ($status) {
                return $q->where('status', $status);
            });
        }

        if (method_exists($this, 'city')) {
            $query->whereHas('city', function ($q) use ($status) {
                return $q->where('status', $status);
            });
        }

        if (method_exists($this, 'ruralDistrict')) {
            $query->whereHas('ruralDistrict', function ($q) use ($status) {
                return $q->where('status', $status);
            });
        }

        return $query;
    }

    public function activate()
    {
        $this->updateStatus(1);

        return $this;
    }

    /**
     * de activates record by updating status to 0
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->updateStatus(0);

        return $this;
    }

    /**
     * checked if record status is 1 : active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->isStatus(1);
    }

    /**
     * checked if record status is 0 : no active
     *
     * @return bool
     */
    public function isNotActive()
    {
        return $this->isStatus(0);
    }

    /**
     * checks the status
     *
     * @param $status
     * @return bool
     */
    private function isStatus($status)
    {
        if ($this->status != $status)
            return false;

        if (method_exists($this, 'parent')) {

            $parent = $this->parent()->first();

            if ($parent && $parent->status != $status)
                return false;
        }

        if (method_exists($this, 'province')) {

            $province = $this->province()->first();

            if ($province && $province->status != $status)
                return false;
        }

        if (method_exists($this, 'county')) {

            $county = $this->county()->first();

            if ($county && $county->status != $status)
                return false;
        }

        if (method_exists($this, 'sector')) {

            $sector = $this->sector()->first();

            if ($sector && $sector->status != $status)
                return false;
        }

        if (method_exists($this, 'city')) {

            $city = $this->city()->first();

            if ($city && $city->status != $status)
                return false;
        }

        if (method_exists($this, 'ruralDistrict')) {

            $ruralDistrict = $this->ruralDistrict()->first();

            if ($ruralDistrict && $ruralDistrict->status != $status)
                return false;
        }

        return true;
    }

    /**
     * updates the status
     *
     * @param $status
     */
    private function updateStatus($status)
    {
        $this->status = $status;

        $this->save();
    }
}
