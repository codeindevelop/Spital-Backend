<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class PaymentGateway extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'gateway_name',
        'description',
        'merchant_id',
        'request_url',
        'start_url',
        'verify_url',
        'inquiry_url',
        'call_back_url',
        'active',
    ];
}
