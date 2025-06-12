<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Page\App\Models\Page;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids;

    protected $fillable = [
        'email',
        'password',
        'last_password_change',
        'active',
        'mobile_number',
    ];

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

    public function personalInfo(): HasOne
    {
        return $this->hasOne(UserPersonalInfo::class, 'user_id', 'id');
    }

    public function verify(): HasOne
    {
        return $this->hasOne(UserVerify::class, 'user_id', 'id');
    }

    public function createdPages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'created_by');
    }

    public function updatedPages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'updated_by');
    }
}
