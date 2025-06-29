<?php

namespace Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Seo\App\Models\page\PageSchema;
use Modules\Seo\App\Models\page\PageSeo;
use Modules\User\App\Models\User;

/**
 * @method static create(array $array)
 * @method static findOrFail(string $id)
 * @method static where(string $string, mixed $parent_id)
 */
class Page extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'order', 'template', 'status',
        'visibility', 'custom_css', 'password', 'custom_js', 'published_at', 'is_active',
        'parent_id', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function seo(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PageSeo::class, 'page_id');
    }

    public function schema(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PageSchema::class, 'page_id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
