<?php

namespace Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Page\App\Models\Page;
use Modules\User\App\Models\User;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $pageId)
 */
class PageSeo extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'page_seos';

    protected $fillable = [
        'page_id', 'created_by', 'meta_title', 'meta_keywords', 'meta_description',
        'canonical_url', 'favicon', 'robots_index', 'robots_follow', 'theme_color',
        'language', 'region', 'timezone', 'author', 'og_title', 'og_description',
        'og_image', 'og_type', 'og_url', 'og_site_name', 'og_locale', 'og_image_alt',
        'og_image_width', 'og_image_height', 'og_image_type', 'twitter_title',
        'twitter_description', 'twitter_site', 'twitter_creator', 'twitter_image',
        'twitter_card_type', 'twitter_site_handle', 'twitter_creator_handle',
        'twitter_image_alt', 'twitter_image_width', 'twitter_image_height', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
