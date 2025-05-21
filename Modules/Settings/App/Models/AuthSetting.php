<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class AuthSetting extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'users_can_login',
        'users_can_signup',
        'admins_can_login',
        'admins_can_signup',
        'users_sigup_type',
        'users_login_type',
        'admins_sigup_type',
        'admins_login_type',
        'enable_reset_password'
    ];
}
