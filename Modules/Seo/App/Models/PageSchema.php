<?php

namespace Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Page\App\Models\Page;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * @method static create(array|string[] $array_merge)
 * @method static where(string $string, string $pageId)
 */
class PageSchema extends Model
{
    protected $table = 'page_schemas';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'page_id',
        'type',
        'title',
        'slug',
        'content',
        'description',
        'data',
        'schema_json',
        'status',
        'visibility',
        'language',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
        'schema_json' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Uuid::uuid4()->toString();
            }
        });
    }

    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
