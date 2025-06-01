<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static create(array $array)
 * @method static where(string $string, int $int)
 * @method static has(string $string)
 * @method static withTrashed()
 * @method static findOrFail(string $id)
 * @method static onlyTrashed()
 * @property mixed $id
 * @property mixed $activation_token
 * @property mixed $first_name
 * @property mixed $mobile_number
 * @property Carbon $suspended_at
 * @property mixed $verify
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'last_password_change',
        'active',
        'mobile_number', // اضافه شده چون توی $logAttributes استفاده شده
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'permissions',
        'last_password_change',
        'deleted_at',
        'last_password',
        'remember_token',
        'updated_at',
        'suspended_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'last_password' => 'hashed',
    ];

    protected array $guard_name = ['api', 'mother'];

    protected static array $logAttributes = [
        'mobile_number',
        'active',
        'email',
        'password',
        'last_password',
    ];

    protected static string $logName = 'auth';

    // Get user personal information
    public function personalInfo(): HasOne
    {
        return $this->hasOne(UserPersonalInfo::class, 'user_id', 'id');
    }

    // Get user verify information
    public function verify(): HasOne
    {
        return $this->hasOne(UserVerify::class, 'user_id', 'id');
    }
}
