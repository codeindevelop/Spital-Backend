<?php

namespace Modules\Eshop\App\Models\Settings\Product;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static first()
 * @method static updateOrCreate(array|null[] $array, array $data)
 */
class EshopProductSetting extends Model
{


    protected $fillable = [

    ];

    use LogsActivity;



    /**
     * Get activity log options.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
