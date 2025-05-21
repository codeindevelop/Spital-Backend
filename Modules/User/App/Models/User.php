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
 * @property mixed $id
 * @property mixed $activation_token
 * @property mixed $first_name
 * @property mixed $mobile_number
 * @property Carbon $suspended_at
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

        'user_name',
        'first_name',
        'last_name',

        'mobile_number',

        'email',
        'password',
        'register_ip',
        'last_password_change',
        'suspended_at',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
//        'roles',
        'permissions',
        'last_password_change',
        'deleted_at',
        'last_password',
        'verifyInfo',

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
        'email_verified_at' => 'datetime',
        'suspended_at' => 'datetime',
        'password' => 'hashed',
        'last_password' => 'hashed',
    ];

//    protected $guard_name = 'api';
    protected array $guard_name = ['api', 'mother'];


    protected static array $logAttributes = [
        'group_id',
        'first_name',
        'last_name',
        'mobile_number',
        'activation_token',
        'active',
        'email',
        'password',
        'last_password',
        'suspended_at',
    ];

    protected static string $logName = 'auth';

    // Get user personal information
    public function personalInfos(): HasOne
    {
        return $this->hasOne(UserPersonalInfo::class,);
    }

    // Get user Verify information
    public function verifyInfo(): HasOne
    {
        return $this->hasOne(UserVerify::class);
    }

    // Suspend user
    public function suspend(): void
    {
        $this->suspended_at = now();
        $this->save();
    }

}
