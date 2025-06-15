<?php

namespace Modules\Blog\App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Seo\App\Models\post\PostSchema;
use Modules\Seo\App\Models\post\PostSeo;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * @method static create(array $data)
 * @method static findOrFail(string $id)
 * @method static where(string $string, string $postId)
 * @property mixed $id
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'author_id', 'category_id', 'title', 'slug', 'content', 'summary', 'featured_image',
        'cover_image_id', 'cover_image_name', 'cover_image_alt', 'cover_image_preview', 'cover_image_width',
        'cover_image_height', 'comment_status', 'likes_count', 'password', 'status', 'visibility',
        'published_at', 'is_active', 'is_featured', 'is_trend', 'is_advertisement', 'reading_time',
        'short_link', 'post_type', 'media_link', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'comment_status' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Uuid::uuid4()->toString();
            }
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = SlugHelper::generatePersianSlug($model->title, self::class, 'slug');
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'post_id')->whereNull('parent_id')->orderBy('created_at', 'asc');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(PostSeo::class, 'post_id');
    }

    public function schema(): HasOne
    {
        return $this->hasOne(PostSchema::class, 'post_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
