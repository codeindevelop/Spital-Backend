<?php

namespace Modules\Leads\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @method static create(string[] $language)
 */
class LeadStatus extends Model
{
    use HasFactory,HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status_name',
        'color',
        'active',
    ];
}
