<?php

namespace Modules\Leads\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leads\Database\factories\LeadSourcesFactory;

/**
 * @method static create(string[] $Source)
 */
class LeadSources extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'source_name',
        'active',
    ];

}
