<?php

namespace Modules\Settings\App\Models\Eshop;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static first()
 * @method static updateOrCreate(array|null[] $array, array $data)
 */
class EshopGeneralSetting extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_logo',
        'store_name',
        'email',
        'phone_number',
        'mobile_number',
        'purchase_mode',
        'address',
        'city',
        'country',
        'province',
        'postal_code',
        'sale_scope',
        'shipping_scope',
        'tax_enabled',
        'coupon_enabled',
        'currency',
    ];

    protected $casts = [
        'tax_enabled' => 'boolean',
        'coupon_enabled' => 'boolean',
        'purchase_mode' => 'string',
        'sale_scope' => 'string',
        'shipping_scope' => 'string',
    ];

    use LogsActivity;

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4()->toString();
            }
        });
    }

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
