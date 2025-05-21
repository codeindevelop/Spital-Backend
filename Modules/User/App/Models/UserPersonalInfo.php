<?php

namespace Modules\User\App\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserPersonalInfo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'user_personal_infos';

    protected $guarded = ["id", "user_id", "created_at", "updated_at"];

//    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
//    {
//        return $this->hasOne(User::class);
//    }

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'nationality',
        'father_name',
        'language_id',
        'gender',

        'phone_number',
        'home_address',

        'team_name',
        'company_name',
        'marriage_type',

        'short_bio',
        'long_bio',
        'born_city',
        'live_city',
        'education_level',
        'university_name',

        'profile_image',
        'cover_image',
    ];


    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
