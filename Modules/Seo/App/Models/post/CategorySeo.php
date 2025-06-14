<?php

namespace Modules\Seo\App\Models\post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\App\Models\PostCategory;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * @method static create(array|string[] $array_merge)
 * @method static where(string $string, string $categoryId)
 */
class CategorySeo extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'og_title',
        'og_description',
        'og_type',
        'og_url',
        'og_site_name',
        'og_image',
        'og_image_alt',
        'og_image_width',
        'og_image_height',
        'og_locale',
        'twitter_card',
        'twitter_description',
        'twitter_site',
        'twitter_creator',
        'twitter_image',
        'generator',
        'created_by',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4()->toString();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
