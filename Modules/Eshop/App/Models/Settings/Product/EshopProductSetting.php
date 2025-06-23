<?php

namespace Modules\Eshop\App\Models\Settings\Product;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class EshopProductSetting extends Model
{
    use  LogsActivity;

    protected $table = 'eshop_product_settings';

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'redirect_to_cart',
        'dynamic_cart',
        'placeholder_image',
        'weight_unit',
        'dimensions_unit',
        'product_reviews',
        'only_owners_can_reviews',
        'show_verified',
        'star_rating_review',
        'star_rating_review_required',
        'manage_stock',
        'hold_stock',
        'low_stock_notification',
        'out_of_stock_notification',
        'low_stock_threshold',
        'out_of_stock_threshold',
        'out_of_stock_visibility',
    ];

    protected $casts = [
        'redirect_to_cart' => 'boolean',
        'dynamic_cart' => 'boolean',
        'product_reviews' => 'boolean',
        'only_owners_can_reviews' => 'boolean',
        'show_verified' => 'boolean',
        'star_rating_review' => 'boolean',
        'star_rating_review_required' => 'boolean',
        'manage_stock' => 'boolean',
        'low_stock_notification' => 'boolean',
        'out_of_stock_notification' => 'boolean',
        'out_of_stock_visibility' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
