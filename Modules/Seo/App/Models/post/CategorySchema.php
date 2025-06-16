<?php

namespace Modules\Seo\App\Models\post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Settings\App\Models\Eshop\Blog\PostCategory;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * @method static create(array|string[] $array_merge)
 * @method static where(string $string, string $categoryId)
 */
class CategorySchema extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'category_id',
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
        'author',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
        'schema_json' => 'array',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
