<?php

namespace Modules\Blog\App\Models;


use App\Helpers\SlugHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Seo\App\Models\post\PostSchema;
use Modules\Seo\App\Models\post\PostSeo;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * @method static create(array $data)
 * @method static findOrFail(string $id)
 * @property mixed $id
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'comment_status',
        'status',
        'visibility',
        'password',
        'published_at',
        'is_active',
        'created_by',
        'updated_by',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
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
