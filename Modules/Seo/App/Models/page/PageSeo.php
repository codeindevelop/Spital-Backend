<?php

namespace Modules\Seo\App\Models\page;

use Illuminate\Database\Eloquent\Model;
use Modules\Page\App\Models\Page;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * @method static where(string $string, string $pageId)
 * @method static create(array|string[] $array_merge)
 */
class PageSeo extends Model
{
    protected $table = 'page_seo';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'page_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'canonical_url',
        'image',
        'robots_index',
        'robots_follow',
        'theme_color',
        'language',
        'region',
        'timezone',
        'author',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_url',
        'og_site_name',
        'og_locale',
        'og_image_alt',
        'og_image_width',
        'og_image_height',
        'og_image_type',
        'twitter_card',
        'twitter_description',
        'twitter_site',
        'twitter_creator',
        'twitter_image',
        'twitter_card_type',
        'twitter_site_handle',
        'twitter_creator_handle',
        'twitter_image_alt',
        'twitter_image_width',
        'twitter_image_height',
        'created_by',
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
