<?php

namespace Modules\Leads\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leads\Database\factories\LeadAttachmentFactory;

class LeadAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): LeadAttachmentFactory
    {
        //return LeadAttachmentFactory::new();
    }
}
